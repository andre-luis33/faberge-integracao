<?php

namespace App\Dtos;

class LinxStockPartDto {
   public string|null $CodigoEstoque;
   public string|null $ItemEstoque;
   public string|null $CodigoEanGtin;
   public string|null $DescricaoItemEstoque;
   public string|null $UtilizacaoItem;
   public string|null $ClassificacaoABC;
   public string|null $ClassificacaoXYZ;
   public string|null $ClassificacaoABC123;
   public string|null $UnidadeMedida;
   public string|null $GrupoDesconto;
   public string|null $PercentualDesconto;
   public string|null $ItemEstoqueSubstituto;
   public string|null $QuantidadeEmbalagem;
   public string|null $Preco;
   public string|null $PrecoMedioContabil;
   public string|null $CustoMedio;
   public string|null $QuantidadeDisponivel;
   public string|null $PrecoPolitica;
   public string|null $PrecoSugerido;
   public string|null $ItemEstoquePublico;
   public string|null $CodigoItemAlternativo;
   public string|null $ItemAlternativo;
   public string|null $BaseTroca;
   public string|null $CodigoTributacao;
   public string|null $MvaPercentual;
   public string|null $BasePis;
   public string|null $BaseCofins;
   public string|null $PrecoAcrescimoPartilha;
   public string|null $NumeroDiasEmAberto;
   public string|null $TributaIPI;
   public string|null $AliquotaIPI;
   public string|null $GrupoDaMontadora;
   public string|null $DepartamentoOrigem;
   public string|null $Valorizacao;
   public string|null $ItemEmPromocao;
   public string|null $CanceladoFabrica;
   public string|null $SubstituidoFiat;
   public string|null $SubstituidoCitroenPeugeot;
   public string|null $CodigoCor;
   public string|null $DescricaoCor;
   public string|null $Marca;
   public string|null $NomeMarca;
   public string|null $PrecoPolitica1;
   public string|null $PrecoPolitica2;
   public string|null $PrecoPolitica3;
   public string|null $PrecoPolitica4;
   public string|null $PoliticaMaisIPI;
   public string|null $ValorIPI;


   public static function fromArray($array): LinxStockPartDto {
      $dto = new self();

      foreach ($array as $key => $value) {
         if (property_exists($dto, $key)) {
            $dto->$key = trim($value);
         }
      }

      return $dto;
   }
}

