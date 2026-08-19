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
interface IProdutoProcessado {
    nome: string;
    preco: number;
    idCategoria: number;
    nomeCategoria: string;
    popular: boolean;
    estoque: number;
}
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
declare function carregarDashboardTS(): Promise<void>;
declare function atualizarDOM(dados: IDadosDashboard): void;
declare function exibirDashboardVazio(): void;
declare function formatarPreco(valor: number): string;
//# sourceMappingURL=dashboard.d.ts.map