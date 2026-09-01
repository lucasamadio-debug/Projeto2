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
        let maiorEstoqueNome = '-';
        let maiorEstoqueQtd = 0;
        let menorEstoqueNome = '-';
        let menorEstoqueQtd = 0;
        if (bebidas.length > 0) {
            const maiorEstoque = bebidas.reduce((atual, produto) => (produto.estoque > atual.estoque ? produto : atual));
            const menorEstoque = bebidas.reduce((atual, produto) => (produto.estoque < atual.estoque ? produto : atual));
            maiorEstoqueNome = maiorEstoque.nome;
            maiorEstoqueQtd = maiorEstoque.estoque;
            menorEstoqueNome = menorEstoque.nome;
            menorEstoqueQtd = menorEstoque.estoque;
        }
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
            estoqueBebidas,
            maiorEstoqueNome,
            maiorEstoqueQtd,
            menorEstoqueNome,
            menorEstoqueQtd
        };
        atualizarDOM(dados);
        renderizarRankingEstoque(bebidas);
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
    const elMaisCaroNome = document.getElementById('dash-mais-caro-nome');
    const elMaisBarato = document.getElementById('dash-mais-barato');
    const elMaisBaratoNome = document.getElementById('dash-mais-barato-nome');
    const elCategorias = document.getElementById('dash-categorias');
    const elTotalBebidas = document.getElementById('dash-total-bebidas');
    const elEstoqueBebidas = document.getElementById('dash-estoque-bebidas');
    const elMaiorEstoque = document.getElementById('dash-maior-estoque');
    const elMenorEstoque = document.getElementById('dash-menor-estoque');
    if (elTotal)
        elTotal.innerText = dados.total.toString();
    if (elMedia)
        elMedia.innerText = formatarPreco(dados.media);
    if (elMaisCaro)
        elMaisCaro.innerText = formatarPreco(dados.maisCaroPreco);
    if (elMaisCaroNome)
        elMaisCaroNome.innerText = dados.maisCaroNome;
    if (elMaisBarato)
        elMaisBarato.innerText = formatarPreco(dados.maisBaratoPreco);
    if (elMaisBaratoNome)
        elMaisBaratoNome.innerText = dados.maisBaratoNome;
    if (elCategorias)
        elCategorias.innerText = dados.totalCategorias.toString();
    if (elTotalBebidas)
        elTotalBebidas.innerText = dados.totalBebidas.toString();
    if (elEstoqueBebidas)
        elEstoqueBebidas.innerText = `${dados.estoqueBebidas} un.`;
    if (elMaiorEstoque)
        elMaiorEstoque.innerText = `${dados.maiorEstoqueNome} (${dados.maiorEstoqueQtd} un.)`;
    if (elMenorEstoque)
        elMenorEstoque.innerText = `${dados.menorEstoqueNome} (${dados.menorEstoqueQtd} un.)`;
}
function renderizarRankingEstoque(bebidas) {
    const corpo = document.getElementById('dash-ranking-estoque');
    if (!corpo)
        return;
    if (bebidas.length === 0) {
        corpo.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">Nenhuma bebida cadastrada</td></tr>';
        return;
    }
    // ordena do maior para o menor estoque, sem alterar o array original
    const ordenadas = [...bebidas].sort((a, b) => b.estoque - a.estoque);
    corpo.innerHTML = ordenadas
        .map((produto, indice) => `
            <tr>
                <td class="ps-3 fw-bold">${indice + 1}º</td>
                <td>${produto.nome}</td>
                <td class="text-end pe-3">${produto.estoque} un.</td>
            </tr>
        `)
        .join('');
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
    const idsNome = ['dash-mais-caro-nome', 'dash-mais-barato-nome', 'dash-maior-estoque', 'dash-menor-estoque'];
    idsNome.forEach((id) => {
        const el = document.getElementById(id);
        if (el)
            el.innerText = '';
    });
    const corpoRanking = document.getElementById('dash-ranking-estoque');
    if (corpoRanking) {
        corpoRanking.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">Nenhum dado registrado</td></tr>';
    }
}
function formatarPreco(valor) {
    return `R$ ${valor.toFixed(2).replace('.', ',')}`;
}
document.addEventListener('DOMContentLoaded', () => {
    carregarDashboardTS();
});
//# sourceMappingURL=dashboard.js.map