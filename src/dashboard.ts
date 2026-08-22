// formato dos dados que vêm do backend PHP
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

// prossesar os dados brutos da API
interface IProdutoProcessado {
    nome: string;
    preco: number;
    idCategoria: number;
    nomeCategoria: string;
    popular: boolean;
    estoque: number;
}

// Dados jogados no HTML
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

        if (!json.sucesso || !Array.isArray(json.dados) || json.dados.length === 0) {
            exibirDashboardVazio();
            return;
        }

        const produtos: IProdutoProcessado[] = json.dados.map((produto) => ({
            nome: produto.nome_lanches ?? 'Sem nome',
            preco: Number(produto.preco) || 0,
            idCategoria: Number(produto.id_categoria) || 0,
            nomeCategoria: (produto.nome_categoria ?? '').toUpperCase(),
            popular: produto.popular === true || produto.popular === 1,
            estoque: Number(produto.quantidade_estoque) || 0
        }));

        const totalLanches = produtos.length;
        const somaPrecos = produtos.reduce((acumulado, produto) => acumulado + produto.preco, 0);
        const media = somaPrecos / totalLanches;

        const maisCaro = produtos.reduce((atual, produto) => (produto.preco > atual.preco ? produto : atual));
        const maisBarato = produtos.reduce((atual, produto) => (produto.preco < atual.preco ? produto : atual));

    
        const produtosAcimaDaMedia = produtos.filter((produto) => produto.preco > media);

  
        const idsCategorias = produtos.map((produto) => produto.idCategoria);
        const totalCategorias = new Set(idsCategorias).size;

        const bebidas = produtos.filter((produto) => produto.nomeCategoria === 'BEBIDAS');
        const totalBebidas = bebidas.length;

        const estoqueBebidas = bebidas.reduce((acumulado, produto) => acumulado + produto.estoque, 0);

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

        console.log("Produtos acima da média de preço: " + produtosAcimaDaMedia.length);
        console.log("Categoria com mais produtos: ID " + categoriaDestaque + " (" + maiorContagem + " produtos)");

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

function formatarPreco(valor: number): string {
    return `R$ ${valor.toFixed(2).replace('.', ',')}`;
}

document.addEventListener('DOMContentLoaded', () => {
    carregarDashboardTS();
});