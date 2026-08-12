interface ILanche {
    num: string;
    nome: string;
    preco: number;
    ingredientes: string;
    popular: boolean;
}

interface ISecaoCardapio {
    secao_titulo: string;
    secao_icon: string;
    lanches: ILanche[];
}

interface IRespostaAPI {
    sucesso: boolean;
    dados: ISecaoCardapio[];
}

async function carregarDashboardTS(): Promise<void> {
    try {
        // Tenta buscar no caminho padrão da API
        let resposta = await fetch('api/produtos.php');
        
        if (!resposta.ok) {
            resposta = await fetch('../api/produtos.php');
        }

        const json: IRespostaAPI = await resposta.json();

        if (json.sucesso && json.dados) {
            const todosLanches: ILanche[] = json.dados.flatMap((secao: ISecaoCardapio) => secao.lanches);

            const somaPrecos: number = todosLanches.reduce((acumulador: number, item: ILanche) => acumulador + item.preco, 0);
            const media: number = todosLanches.length > 0 ? (somaPrecos / todosLanches.length) : 0;

            const elTotal = document.getElementById('dash-total');
            const elMedia = document.getElementById('dash-media');

            if (elTotal) {
                elTotal.innerText = todosLanches.length.toString();
            }

            if (elMedia) {
                elMedia.innerText = `R$ ${media.toFixed(2).replace('.', ',')}`;
            }
        }
    } catch (erro) {
        console.error("Erro no Dashboard TS:", erro);
    }
}

document.addEventListener('DOMContentLoaded', (): void => {
    carregarDashboardTS();
});