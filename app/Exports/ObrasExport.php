<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ObrasExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $obras;

    public function __construct($obras)
    {
        $this->obras = $obras;
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->obras as $obra) {
            $data[] = [
                $obra->id_projeto ?? '',
                $obra->nome_projeto ?? '',
                $obra->objeto ?? '',
                $obra->area_tematica ?? '',
                $obra->situacao_obra ?? '',
                $obra->municipio ?? '',
                $obra->data_de_inicio_ou_previsao ?? '',
                $obra->data_prevista_conclusao ?? '',
                $obra->valor_total_do_projeto ? 'R$ ' . number_format($obra->valor_total_do_projeto, 2, ',', '.') : '',
                $obra->valor_pago ? 'R$ ' . number_format($obra->valor_pago, 2, ',', '.') : '',
                $obra->saldo_a_pagar ? 'R$ ' . number_format($obra->saldo_a_pagar, 2, ',', '.') : '',
                $obra->estagio_execucao_percentual ? $obra->estagio_execucao_percentual . '%' : '',
                $obra->latitude ?? '',
                $obra->longitude ?? '',
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'ID Projeto',
            'Nome do Projeto',
            'Objeto',
            'Área Temática',
            'Situação',
            'Município',
            'Data Início',
            'Data Previsão Conclusão',
            'Valor Total',
            'Valor Pago',
            'Saldo a Pagar',
            'Execução (%)',
            'Latitude',
            'Longitude',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // ID Projeto
            'B' => 40,  // Nome do Projeto
            'C' => 50,  // Objeto
            'D' => 20,  // Área Temática
            'E' => 15,  // Situação
            'F' => 20,  // Município
            'G' => 15,  // Data Início
            'H' => 20,  // Data Previsão
            'I' => 15,  // Valor Total
            'J' => 15,  // Valor Pago
            'K' => 15,  // Saldo a Pagar
            'L' => 12,  // Execução
            'M' => 15,  // Latitude
            'N' => 15,  // Longitude
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E3F2FD',
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Aplicar bordas em todas as células com dados
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);

                // Congelar primeira linha
                $sheet->freezePane('A2');

                // Aplicar filtros automáticos
                $sheet->setAutoFilter('A1:' . $lastColumn . $lastRow);
            },
        ];
    }
}
