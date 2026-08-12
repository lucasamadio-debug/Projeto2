async function carregarDashboardTS() {
    try {
        // Tenta buscar no caminho padrão da API
        let resposta = await fetch('api/produtos.php');
        if (!resposta.ok) {
            resposta = await fetch('../api/produtos.php');
        }
        const json = await resposta.json();
        if (json.sucesso && json.dados) {
            const todosLanches = json.dados.flatMap((secao) => secao.lanches);
            const somaPrecos = todosLanches.reduce((acumulador, item) => acumulador + item.preco, 0);
            const media = todosLanches.length > 0 ? (somaPrecos / todosLanches.length) : 0;
            const elTotal = document.getElementById('dash-total');
            const elMedia = document.getElementById('dash-media');
            if (elTotal) {
                elTotal.innerText = todosLanches.length.toString();
            }
            if (elMedia) {
                elMedia.innerText = `R$ ${media.toFixed(2).replace('.', ',')}`;
            }
        }
    }
    catch (erro) {
        console.error("Erro no Dashboard TS:", erro);
    }
}
document.addEventListener('DOMContentLoaded', () => {
    carregarDashboardTS();
});
export {};
//# sourceMappingURL=dashboard.js.map