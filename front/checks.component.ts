import { Component, OnInit, Input } from '@angular/core';
import { Cheque } from 'src/app/interfaces/cheque';
import { Cliente } from 'src/app/interfaces/cliente';
import { ChequesService } from 'src/app/services/cheques.service';
import { ColumnMode } from '@swimlane/ngx-datatable';
import { error } from 'console';
import { format } from 'path';

@Component({
  selector: 'app-checks',
  templateUrl: './checks.component.html',
  styleUrls: ['./checks.component.scss']
})
export class ChecksComponent implements OnInit {
  @Input() clientes: Cliente[];
  @Input() selectedClienteId : number;
  clientName = "";
  cheques : Cheque[] = [];
  ColumnMode = ColumnMode;
  loading = true;
  selectedCheck : Cheque;

  constructor(private chequeService : ChequesService) { }

  ngOnInit(): void {
    let selectedClient = this.clientes.find(c => c.id == this.selectedClienteId);
    if (selectedClient)
      this.clientName = selectedClient.razon_social;

    this.chequeService.getByCliente(this.selectedClienteId).subscribe(
      response => {
        this.cheques = response;
        this.cheques.map(check => {
          let cliente = this.clientes.find(c => c.id == check.id_cliente);
          check.nombre_cliente = cliente ? cliente.razon_social : "";
          check.numeroToShow = `${check.numero} - ${check.serie}`
          check.fechaPagoToShow = this.getFormattedDate(new Date(check.fecha_pago));
          check.fechaEmisionToShow = this.getFormattedDate(new Date(check.fecha_emision));
          check.selected = false;
        });
        this.loading = false;
      }
    );
  }

  getFormattedDate(date : Date) : string {
    let dateNumber = date.getDate() < 10 ? `0${date.getDate()}` : date.getDate().toString();
    let month = date.getMonth() + 1 < 10 ? `0${date.getMonth() + 1}` : (date.getMonth() + 1).toString();

    let formatedDate = `${dateNumber}/${month}/${date.getFullYear()}`;
    return formatedDate;
  }

  seleccionarCheque(cheque : Cheque) {
    cheque.selected = true;
  } 

  deseleccionarCheque(cheque : Cheque) {
    cheque.selected = false;
  }

  verDetalleCheque(cheque : Cheque) {
    this.selectedCheck = cheque;
  }
}
