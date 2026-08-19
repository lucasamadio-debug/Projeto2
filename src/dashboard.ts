// Interfaces que definem o formato dos dados que vêm do backend PHP
interface IProduto {
    id_produto?: number;
    nome_lanches?: string;
    preco: number | string;
    id_categoria?: number;
    nome_categoria?: string;
    popular?: boolean | number;
    quantidade_estoque?: number | string;
}

interface IRespostaAPI {
    sucesso: boolean;
    dados: IProduto[];
}

// Formato "limpo" e confiável, depois de processarmos os dados brutos da API
interface IProdutoProcessado {
    nome: string;
    preco: number;
    idCategoria: number;
    nomeCategoria: string;
    popular: boolean;
    estoque: number;
}

// Dados já prontos pra jogar no HTML
interface IDadosDashboard {
    total: number;
    media: number;
    maisCaroNome: string;
    maisCaroPreco: number;
    maisBaratoNome: string;
    maisBaratoPreco: number;
    totalCategorias: number;
    totalBebidas: number;
    estoqueBebidas: number;
}

async function carregarDashboardTS(): Promise<void> {
    try {
        let resposta = await fetch('api/produtos.php');
        if (!resposta.ok) {
            resposta = await fetch('../api/produtos.php');
        }

        const json: IRespostaAPI = await resposta.json();

        // EDGE CASE: sem sucesso, sem array, ou array vazio -> mensagem
        if (!json.sucesso || !Array.isArray(json.dados) || json.dados.length === 0) {
            exibirDashboardVazio();
            return;
        }

        // MAP: transforma o array bruto da API (preco pode vir como string)
        // num array limpo e tipado, pronto pra ser usado no resto do código
        const produtos: IProdutoProcessado[] = json.dados.map((produto) => ({
            nome: produto.nome_lanches ?? 'Sem nome',
            preco: Number(produto.preco) || 0,
            idCategoria: Number(produto.id_categoria) || 0,
            nomeCategoria: (produto.nome_categoria ?? '').toUpperCase(),
            popular: produto.popular === true || produto.popular === 1,
            estoque: Number(produto.quantidade_estoque) || 0
        }));

        // REDUCE: soma o valor de todos os lanches (cálculo financeiro
        // consolidado) pra depois calcular a média
        const totalLanches = produtos.length;
        const somaPrecos = produtos.reduce((acumulado, produto) => acumulado + produto.preco, 0);
        const media = somaPrecos / totalLanches;

        // REDUCE: acha o produto inteiro (não só o número) mais caro e mais
        // barato, comparando um item contra o outro
        const maisCaro = produtos.reduce((atual, produto) => (produto.preco > atual.preco ? produto : atual));
        const maisBarato = produtos.reduce((atual, produto) => (produto.preco < atual.preco ? produto : atual));

        // FILTER: segmenta os produtos que estão com preço acima da média
        const produtosAcimaDaMedia = produtos.filter((produto) => produto.preco > media);

        // MAP: extrai só os ids de categoria, pra contar quantas categorias
        // diferentes existem sem duplicar
        const idsCategorias = produtos.map((produto) => produto.idCategoria);
        const totalCategorias = new Set(idsCategorias).size;

        // FILTER: separa só os produtos da categoria BEBIDAS
        const bebidas = produtos.filter((produto) => produto.nomeCategoria === 'BEBIDAS');
        const totalBebidas = bebidas.length;

        // REDUCE: soma a quantidade em estoque só das bebidas
        const estoqueBebidas = bebidas.reduce((acumulado, produto) => acumulado + produto.estoque, 0);

        // REDUCE + objeto chave-valor: ranking de frequência -> qual
        // categoria tem mais produtos cadastrados (indicador de destaque)
        const contagemPorCategoria = produtos.reduce((contador: Record<number, number>, produto) => {
            contador[produto.idCategoria] = (contador[produto.idCategoria] || 0) + 1;
            return contador;
        }, {});

        let categoriaDestaque = 0;
        let maiorContagem = 0;
        for (const idCategoria in contagemPorCategoria) {
            if (contagemPorCategoria[idCategoria] > maiorContagem) {
                maiorContagem = contagemPorCategoria[idCategoria];
                categoriaDestaque = Number(idCategoria);
            }
        }

        console.log(`Produtos acima da média de preço: ${produtosAcimaDaMedia.length}`);
        console.log(`Categoria com mais produtos: ID ${categoriaDestaque} (${maiorContagem} produtos)`);

        const dados: IDadosDashboard = {
            total: totalLanches,
            media,
            maisCaroNome: maisCaro.nome,
            maisCaroPreco: maisCaro.preco,
            maisBaratoNome: maisBarato.nome,
            maisBaratoPreco: maisBarato.preco,
            totalCategorias,
            totalBebidas,
            estoqueBebidas
        };

        atualizarDOM(dados);

    } catch (erro) {
        console.error("Erro ao carregar o dashboard:", erro);
        exibirDashboardVazio();
    }
}

// Só cuida de colocar os dados já calculados no HTML (responsabilidade única)
function atualizarDOM(dados: IDadosDashboard): void {
    const elTotal = document.getElementById('dash-total');
    const elMedia = document.getElementById('dash-media');
    const elMaisCaro = document.getElementById('dash-mais-caro');
    const elMaisBarato = document.getElementById('dash-mais-barato');
    const elCategorias = document.getElementById('dash-categorias');
    const elTotalBebidas = document.getElementById('dash-total-bebidas');
    const elEstoqueBebidas = document.getElementById('dash-estoque-bebidas');

    if (elTotal) elTotal.innerText = dados.total.toString();
    if (elMedia) elMedia.innerText = formatarPreco(dados.media);
    if (elMaisCaro) elMaisCaro.innerText = `${formatarPreco(dados.maisCaroPreco)} (${dados.maisCaroNome})`;
    if (elMaisBarato) elMaisBarato.innerText = `${formatarPreco(dados.maisBaratoPreco)} (${dados.maisBaratoNome})`;
    if (elCategorias) elCategorias.innerText = dados.totalCategorias.toString();
    if (elTotalBebidas) elTotalBebidas.innerText = dados.totalBebidas.toString();
    if (elEstoqueBebidas) elEstoqueBebidas.innerText = `${dados.estoqueBebidas} un.`;
}

// EDGE CASE: sem dados (banco vazio ou erro na API) -> mensagem elegante
// em vez de deixar os cards com "0" sem explicação
function exibirDashboardVazio(): void {
    const ids = [
        'dash-total', 'dash-media', 'dash-mais-caro', 'dash-mais-barato',
        'dash-categorias', 'dash-total-bebidas', 'dash-estoque-bebidas'
    ];
    ids.forEach((id) => {
        const el = document.getElementById(id);
        if (el) el.innerText = 'Nenhum dado registrado';
    });
}

// Função auxiliar reaproveitada em vários pontos (evita repetir a mesma
// lógica de formatação em cada lugar que precisa mostrar um preço)
function formatarPreco(valor: number): string {
    return `R$ ${valor.toFixed(2).replace('.', ',')}`;
}

document.addEventListener('DOMContentLoaded', () => {
    carregarDashboardTS();
});