import { Component, OnInit, Input, TemplateRef } from '@angular/core';
import { NgbActiveModal, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PaymentDto } from 'src/app/core/data-transfer-objects/paymentDto';
import { Cliente } from 'src/app/interfaces/cliente';
import { ChecksComponent } from './checks.component';

@Component({
  selector: 'app-payment',
  templateUrl: './payment.component.html',
  styleUrls: ['./payment.component.scss']
})
export class PaymentComponent implements OnInit {
  @Input() clientes: Cliente[];
  @Input() selectedClienteId: number;
  @Input() totalOperacion: string;
  @Input() editing: boolean;
  @Input() type: number;
  public payment: PaymentDto = new PaymentDto();
  sumaCheques: number;
  activeModal: NgbActiveModal;
  someCheckWasSelected = false;
  public selectedChecksIds: number[];
  public notes = "";
  total = "0";

  constructor(private modalService: NgbModal) { }

  ngOnInit(): void {
  }

  openChecksList(content: TemplateRef<any>) {
    this.activeModal = this.modalService.open(content, { scrollable: true, size: 'lg' });
  }

  calculateTotal() {
    let total = this.payment.cheque + this.payment.cuentaCorriente + this.payment.efectivo + this.payment.tarjeta + this.payment.otro;
    this.payment.totalPago = total;
    this.total = total.toFixed(2);
  }

  save(content: TemplateRef<any>, chequesForm: ChecksComponent) {
    this.selectedChecksIds = [];
    this.sumaCheques = 0;
    this.someCheckWasSelected = false;
    chequesForm.cheques.forEach(check => {
      if (check.selected) {
        this.someCheckWasSelected = true;
        this.selectedChecksIds.push(check.id);
        this.sumaCheques += check.importe;
      }
    })
    this.payment.chequeIds = this.selectedChecksIds;
    this.payment.cheque = this.sumaCheques;
    this.activeModal.close();
    this.calculateTotal();
  }
}
