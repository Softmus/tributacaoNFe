# tributacaoNFe
Classe que faz os cálculos dos impostos da Nota Fiscal Eletrônica

A idéia é que essa classe possa facilitar a vida de quem está iniciando nesse mundo, como não encontrei nada pronto resolvi desenvolver e disponibilizar a comunidade.

Espero que possa ajudar também ao projeto NFePHP.


Exemplo de uso:


	  $cImp= new Tributacao;
   
    // Base de Cálculo
    $cImp->vBC = 100;
    
    // PIS
    $cImp->cstPIS = '01';
    $cImp->pPIS = 0.65;
   
    // COFINS
    $cImp->cstCOFINS = '02';
    $cImp->pCOFINS = 7.65;

    // IPI
    $cImp->cstIPI = '02';
    $cImp->pIPI = 0.5;
    
    // ICMS
    $cImp->cstICMS = '201';
	  $cImp->pICMS = 18;
    $cImp->pICMSST = 18;

    // ICMSST
    $cImp->pMVAST = 100;
    $cImp->pRedBCST = 10;
    $cImp->pRedBC = 10;
  
   
    $cImp->CalculaTributos();
    
    echo $cImp->vICMS; 
    echo $cImp->vBCST; 
    echo $cImp->vICMSST;

