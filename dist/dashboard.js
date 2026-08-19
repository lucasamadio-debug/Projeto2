"use strict";
async function carregarDashboardTS() {
    try {
        let resposta = await fetch('api/produtos.php');
        if (!resposta.ok) {
            resposta = await fetch('../api/produtos.php');
        }
        const json = await resposta.json();
        if (!json.sucesso || !Array.isArray(json.dados) || json.dados.length === 0) {
            exibirDashboardVazio();
            return;
        }
        const produtos = json.dados.map((produto) => { var _a, _b; return ({
            nome: (_a = produto.nome_lanches) !== null && _a !== void 0 ? _a : 'Sem nome',
            preco: Number(produto.preco) || 0,
            idCategoria: Number(produto.id_categoria) || 0,
            nomeCategoria: ((_b = produto.nome_categoria) !== null && _b !== void 0 ? _b : '').toUpperCase(),
            popular: produto.popular === true || produto.popular === 1,
            estoque: Number(produto.quantidade_estoque) || 0
        }); });
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
        const contagemPorCategoria = produtos.reduce((contador, produto) => {
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
        const dados = {
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
    }
    catch (erro) {
        console.error("Erro ao carregar o dashboard:", erro);
        exibirDashboardVazio();
    }
}
function atualizarDOM(dados) {
    const elTotal = document.getElementById('dash-total');
    const elMedia = document.getElementById('dash-media');
    const elMaisCaro = document.getElementById('dash-mais-caro');
    const elMaisBarato = document.getElementById('dash-mais-barato');
    const elCategorias = document.getElementById('dash-categorias');
    const elTotalBebidas = document.getElementById('dash-total-bebidas');
    const elEstoqueBebidas = document.getElementById('dash-estoque-bebidas');
    if (elTotal)
        elTotal.innerText = dados.total.toString();
    if (elMedia)
        elMedia.innerText = formatarPreco(dados.media);
    if (elMaisCaro)
        elMaisCaro.innerText = `${formatarPreco(dados.maisCaroPreco)} (${dados.maisCaroNome})`;
    if (elMaisBarato)
        elMaisBarato.innerText = `${formatarPreco(dados.maisBaratoPreco)} (${dados.maisBaratoNome})`;
    if (elCategorias)
        elCategorias.innerText = dados.totalCategorias.toString();
    if (elTotalBebidas)
        elTotalBebidas.innerText = dados.totalBebidas.toString();
    if (elEstoqueBebidas)
        elEstoqueBebidas.innerText = `${dados.estoqueBebidas} un.`;
}
function exibirDashboardVazio() {
    const ids = [
        'dash-total', 'dash-media', 'dash-mais-caro', 'dash-mais-barato',
        'dash-categorias', 'dash-total-bebidas', 'dash-estoque-bebidas'
    ];
    ids.forEach((id) => {
        const el = document.getElementById(id);
        if (el)
            el.innerText = 'Nenhum dado registrado';
    });
}
function formatarPreco(valor) {
    return `R$ ${valor.toFixed(2).replace('.', ',')}`;
}
document.addEventListener('DOMContentLoaded', () => {
    carregarDashboardTS();
});
//# sourceMappingURL=dashboard.js.map