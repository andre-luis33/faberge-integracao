<?php

namespace App\Dtos;

class LinxStockPartDto {
   public int $CodigoEstoque;
   public string $ItemEstoque;
   public string $CodigoEanGtin;
   public string $DescricaoItemEstoque;
   public string $UtilizacaoItem;
   public string $ClassificacaoABC;
   public string $ClassificacaoXYZ;
   public string $ClassificacaoABC123;
   public string $UnidadeMedida;
   public string $GrupoDesconto;
   public float $PercentualDesconto;
   public int $ItemEstoqueSubstituto;
   public int $QuantidadeEmbalagem;
   public float $Preco;
   public float $PrecoMedioContabil;
   public float $CustoMedio;
   public int $QuantidadeDisponivel;
   public float $PrecoPolitica;
   public float $PrecoSugerido;
   public string $ItemEstoquePublico;
   public int $CodigoItemAlternativo;
   public string $ItemAlternativo;
   public string $BaseTroca;
   public int $CodigoTributacao;
   public float $MvaPercentual;
   public float $BasePis;
   public float $BaseCofins;
   public float $PrecoAcrescimoPartilha;
   public int $NumeroDiasEmAberto;
   public string $TributaIPI;
   public float $AliquotaIPI;
   public string $GrupoDaMontadora;
   public string $DepartamentoOrigem;
   public string $Valorizacao;
   public string $ItemEmPromocao;
   public string $CanceladoFabrica;
   public string $SubstituidoFiat;
   public string $SubstituidoCitroenPeugeot;
   public int $CodigoCor;
   public string $DescricaoCor;
   public string $Marca;
   public string $NomeMarca;
   public float $PrecoPolitica1;
   public float $PrecoPolitica2;
   public float $PrecoPolitica3;
   public float $PrecoPolitica4;
   public float $PoliticaMaisIPI;
   public float $ValorIPI;


   public static function fromArray($array): LinxStockPartDto {
      $dto = new self();

      foreach ($array as $key => $value) {
         if (property_exists($dto, $key)) {
            $dto->$key = $value;
         }
      }

      return $dto;
   }
}

