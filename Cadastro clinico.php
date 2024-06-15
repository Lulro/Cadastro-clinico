// https://www.onlinegdb.com/online_php_interpreter

<?php
// Função para calcular o IMC
function calcularIMC($peso, $altura) {
    return $peso / ($altura * $altura);
}

// Função para classificar o IMC
function classificarIMC($imc) {
    if ($imc < 18.5) {
        return "Abaixo do Peso";
    } elseif ($imc >= 18.5 && $imc <= 24.9) {
        return "Peso Normal";
    } elseif ($imc >= 25 && $imc <= 29.9) {
        return "Sobrepeso";
    } elseif ($imc >= 30 && $imc <= 34.9) {
        return "Obesidade Grau 1";
    } elseif ($imc >= 35 && $imc <= 39.9) {
        return "Obesidade Grau 2";
    } else {
        return "Obesidade Grau 3";
    }
}

// Função para exibir o menu
function exibirMenu() {
    echo "\n    -  MENU  -\n";
    echo "  1. Cadastrar nova pessoa\n";
    echo "  2. Exibir tabela\n";
    echo "  3. Salvar dados em arquivo\n";
    echo "  4. Carregar dados de arquivo\n";
    echo "  5. Buscar pessoa por nome\n";
    echo "  6. Sair\n";
    echo "\n Escolha uma opção: ";
}

// Função para salvar dados em arquivo CSV
function salvarEmArquivo($pessoas) {
    $arquivo = fopen('dados_pessoas.csv', 'w');
    fputcsv($arquivo, ['Nome Completo', 'Sexo', 'Telefone', 'Peso (kg)', 'Altura (m)', 'IMC', 'Classificação']);
    foreach ($pessoas as $pessoa) {
        fputcsv($arquivo, $pessoa);
    }
    fclose($arquivo);
    echo "Dados salvos em 'dados_pessoas.csv'.\n";
}

// Função para carregar dados de arquivo CSV
function carregarDeArquivo() {
    $pessoas = [];
    if (($arquivo = fopen('dados_pessoas.csv', 'r')) !== FALSE) {
        fgetcsv($arquivo); // Pula a primeira linha (cabeçalhos)
        while (($dados = fgetcsv($arquivo, 1000, ",")) !== FALSE) {
            $pessoas[] = [
                'nome' => $dados[0],
                'sexo' => $dados[1],
                'telefone' => $dados[2],
                'peso' => $dados[3],
                'altura' => $dados[4],
                'imc' => $dados[5],
                'classificacao' => $dados[6]
            ];
        }
        fclose($arquivo);
        echo "Dados carregados de 'dados_pessoas.csv'.\n";
    } else {
        echo "Arquivo 'dados_pessoas.csv' não encontrado.\n";
    }
    return $pessoas;
}

// Função para buscar pessoa por nome
function buscarPorNome($pessoas, $nome) {
    foreach ($pessoas as $pessoa) {
        if (strtolower($pessoa['nome']) == strtolower($nome)) {
            return $pessoa;
        }
    }
    return null;
}

// Função para exibir tabela
function exibirTabela($pessoas) {
    echo "\n CADASTRO MÉDICO DOS CLIENTES \n";
    echo "-----------------------------------------------------------------------------------\n";
    echo "| NOME COMPLETO       | SEXO | TELEFONE     | PESO (kg) | ALTURA (m) | IMC   | CLASSIFICAÇÃO (IMC)         |\n";
    echo "-----------------------------------------------------------------------------------\n";
    foreach ($pessoas as $pessoa) {
        printf("| %-20s | %-4s | %-12s | %-9s | %-10s | %-5.2f | %-20s |\n", 
            $pessoa['nome'], 
            $pessoa['sexo'], 
            $pessoa['telefone'], 
            $pessoa['peso'], 
            $pessoa['altura'], 
            $pessoa['imc'],
            $pessoa['classificacao']);
    }
    echo "-----------------------------------------------------------------------------------\n";
}

// Main
$pessoas = [];

while (true) {
    exibirMenu();
    $opcao = trim(fgets(STDIN));
    
    switch ($opcao) {
        case 1:
            echo "\nDigite o nome completo da pessoa: ";
            $nome = trim(fgets(STDIN));
            
            echo "Digite o sexo (M/F): ";
            $sexo = trim(fgets(STDIN));
            
            echo "Digite o telefone: ";
            $telefone = trim(fgets(STDIN));
            
            echo "Digite o peso (em kg): ";
            $peso = trim(fgets(STDIN));
            
            echo "Digite a altura (em metros): ";
            $altura = trim(fgets(STDIN));
            
            $imc = calcularIMC($peso, $altura);
            $classificacao = classificarIMC($imc);
            
            $pessoas[] = [
                'nome' => $nome,
                'sexo' => $sexo,
                'telefone' => $telefone,
                'peso' => $peso,
                'altura' => $altura,
                'imc' => $imc,
                'classificacao' => $classificacao
            ];
            break;
        case 2:
            exibirTabela($pessoas);
            break;
        case 3:
            salvarEmArquivo($pessoas);
            break;
        case 4:
            $pessoas = carregarDeArquivo();
            break;
        case 5:
            echo "Digite o nome da pessoa a ser buscada: ";
            $nomeBusca = trim(fgets(STDIN));
            $pessoaEncontrada = buscarPorNome($pessoas, $nomeBusca);
            if ($pessoaEncontrada) {
                echo "\n    Pessoa Encontrada:\n";
                echo "--------------------------\n";
                printf("Nome: %s\nSexo: %s\nTelefone: %s\nPeso: %s kg\nAltura: %s m\nIMC: %.2f\nClassificação: %s\n", 
                    $pessoaEncontrada['nome'], 
                    $pessoaEncontrada['sexo'], 
                    $pessoaEncontrada['telefone'], 
                    $pessoaEncontrada['peso'], 
                    $pessoaEncontrada['altura'], 
                    $pessoaEncontrada['imc'],
                    $pessoaEncontrada['classificacao']);
            } else {
                echo "Pessoa não encontrada.\n";
            }
            break;
        case 6:
            exit("Saindo...\n");
        default:
            echo "Opção inválida. Por favor, escolha uma opção válida.\n";
    }
}
?>
