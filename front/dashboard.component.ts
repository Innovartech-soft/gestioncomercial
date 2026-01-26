import { Component, ElementRef, OnInit, TemplateRef, HostListener, ViewChild } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { NgSelectComponent, NgSelectConfig } from '@ng-select/ng-select';
import { ColumnMode, isNullOrUndefined } from '@swimlane/ngx-datatable';
import { ProductDto } from 'src/app/core/data-transfer-objects/productDto';
import { Cliente } from 'src/app/interfaces/cliente';
import { Lista } from 'src/app/interfaces/lista';
import { Marca } from 'src/app/interfaces/marca';
import { Producto } from 'src/app/interfaces/producto';
import { Proveedor } from 'src/app/interfaces/proveedor';
import { Rubro } from 'src/app/interfaces/rubro';
import { ClientesService } from 'src/app/services/clientes.service';
import { ListasService } from 'src/app/services/listas.service';
import { MarcasService } from 'src/app/services/marca.service';
import { ProductosService } from 'src/app/services/productos.service';
import { ProveedoresService } from 'src/app/services/proveedores.service';
import { RubrosService } from 'src/app/services/rubros.service';
import Swal from 'sweetalert2';
import { forkJoin, of } from 'rxjs';
import { Vendedor } from 'src/app/interfaces/vendedor';
import { VendedoresService } from 'src/app/services/vendedores.service';
import { NgbActiveModal, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PaymentComponent } from './payment.component';
import { RegisterDto } from 'src/app/core/data-transfer-objects/registerDto';
import { clientDto } from 'src/app/core/data-transfer-objects/clientDto';
import { VentasService } from 'src/app/services/ventas.service';
import { TestService } from 'src/app/services/test.service';
import { TipoVenta } from 'src/app/interfaces/tipoVenta';
import { ProductsIdListDto } from 'src/app/core/data-transfer-objects/productsIdListDto';
import { ElementSchemaRegistry } from '@angular/compiler';
import { StockDetail } from 'src/app/interfaces/stockDetail';
import { ProductQuantity } from 'src/app/util/productQuantity';


@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss'],
  preserveWhitespaces: true
})
export class DashboardComponent implements OnInit {
  @ViewChild("productSelector") productSelector: NgSelectComponent;
  @ViewChild("btnGenerar") btnGenerar: ElementRef;
  @ViewChild("clientSelector") clientSelector: NgSelectComponent;
  @HostListener('document:keydown', ['$event'])
  handleKeyboardEvent(event: KeyboardEvent) {
    if (event.key === 'F8') {
      event.preventDefault();
      this.productSelector.open();
    }
    if (event.key === 'F9') {
      event.preventDefault();
      this.btnGenerar.nativeElement.click(); // Realiza el enfoque en el botón.
    }
    if (event.key === 'F10') {
      event.preventDefault();
      this.clientSelector.open(); // Realiza el enfoque en el botón.
    }
  }

  ColumnMode = ColumnMode;
  //validadores
  cuitInvalido = false;
  razonSocialInvalida = false;
  disabled = false;
  //fin validadores
  todayDate: string = this.getTodayDate();
  loading = true;
  editing = false;
  editedOnce = false;
  allowTypeChange = false;
  pagada = false;
  error = false;
  selectedType = 0;
  selectedTypeName = "";
  selectedVendedorId = 0;
  selectedCliente: Cliente;
  selectedClienteId: number;
  selectedMarca = 0;
  selectedRubro = 0;
  selectedProveedor = 0;
  selectedLista = 0;
  searchInput = "";
  selectedStock: number | null;
  clientes: Cliente[] = [];
  vendedores: Vendedor[] = [];
  tipos: TipoVenta[] = [];
  listas: Lista[] = [];
  marcas: Marca[] = [];
  rubros: Rubro[] = [];
  productsQuantityBeforeEditing: ProductQuantity[] = [];
  proveedores: Proveedor[] = [];
  productos: Producto[] = [];
  productosToShow: Producto[] = [];
  selectedSearchProductId: number;
  selectedProduct: Producto | null;
  register: RegisterDto;
  confirmationMessage: string = "";
  registerId: number;
  optionsModal: NgbActiveModal;

  //productos
  cantidad: number | null = null;
  precio: string = "";
  ganancia: number = 0;
  descuento: number | null = null;
  subtotal: string = "";
  precioXUnidad: string = "";
  test = false;
  totalView: string = "";
  total: number = 0;

  productRows: ProductDto[] = [];

  constructor(private route: ActivatedRoute,
    private productosService: ProductosService,
    private rubrosService: RubrosService,
    private marcasService: MarcasService,
    private proveedoresService: ProveedoresService,
    private clientesService: ClientesService,
    private vendedoresService: VendedoresService,
    private listasService: ListasService,
    private ventasService: VentasService,
    private testService: TestService,
    private selectConfig: NgSelectConfig,
    private modalService: NgbModal) {
    this.selectConfig.notFoundText = "No se encontraron productos";

  }

  ngOnInit(): void {
    //this.clientSelector.clearable = false;
    const token = this.route.snapshot.queryParams.token;
    const registerId = this.route.snapshot.queryParams.regId;
    localStorage.setItem('access_token', token);

    if (!token) {
      this.loading = false;
      console.log("Informacion faltante: token");
      this.error = true;
      return;
    }

    forkJoin(
      {
        tiposVenta: this.ventasService.getTipoVentas(),
        listas: this.listasService.getAll(),
        vendedores: this.vendedoresService.getAll(),
        clientes: this.clientesService.getAll(),
        marcas: this.marcasService.getAll(),
        rubros: this.rubrosService.getAll(),
        proveedores: this.proveedoresService.getAll(),
        productos: this.productosService.getAll(),
      }
    ).subscribe(
      {
        next: response => {

          this.tipos = response.tiposVenta;
          this.listas = response.listas;
          this.vendedores = response.vendedores.filter(v => v.estado == true);
          this.clientes = response.clientes;
          this.marcas = response.marcas;
          this.rubros = response.rubros;
          this.proveedores = response.proveedores;
          this.productos = response.productos;
          this.productos = this.productos.map(
            (producto: Producto) => {
              producto.nametoShow = `${producto.nombre} - ${producto.codigo ? producto.codigo + " -" : ""} ${producto.codigo_interno}`;
              return producto;
            }
          );

          if (registerId) {
            this.editing = true;
            this.registerId = registerId;
            this.loadData(registerId);
          }
          else {
            this.loading = false;
          }
        },
        error: error => {
          console.log(error);
          this.error = true;
          this.loading = false;
        }
      }
    );
  }

  loadData(registerId: number) {

    this.ventasService.getVenta(registerId).subscribe(
      venta => {

        if (venta.updated_at > venta.created_at)
          this.editedOnce = true;


        this.selectedType = venta.tipo;
        if (this.selectedType == 2)
          this.allowTypeChange = true;

        this.typeSelected();
        this.selectedClienteId = venta.cliente.id;
        this.clienteSelected();
        this.selectedVendedorId = venta.idVendedor;

        if (venta.pagada == 1)
          this.pagada = true;

        venta.productos.forEach(prod => {
          this.saveQuantityDataBeforeEditing(prod.id, prod.cantidad);

          let product = new ProductDto();
          product.id = prod.id;
          product.cantidad = prod.cantidad;
          let productInList = this.productos.find(p => p.id == prod.id);
          product.descripcion = productInList.nombre;
          product.idLista = prod.idLista;

          product.descuentoPorcentual = prod.descuentoPorcentual;
          product.descuentoNominal = prod.descuentoNominal;
          product.precioUnitario = prod.precioUnitario;
          product.precioUnitarioOriginal = productInList.precio_costo_final;
          product.ofertaAplicada = prod.ofertaAplicada;
          product.precioOferta = productInList.precio_costo_oferta;
          product.enOferta = productInList.en_oferta;
          let list = this.listas.find(l => l.id == prod.idLista)
          product.subtotal = this.getCalculatedPrice(prod.precioUnitario, prod.cantidad, list.valor, prod.descuentoPorcentual) - prod.descuentoNominal;
          product.subtotalVista = product.subtotal.toFixed(1);
          this.productRows.push(product);
        });

        this.calculateTotal();
        this.loading = false;
      }
    );


  }

  saveQuantityDataBeforeEditing(productId: number, productQuantity: number) {
    let productData = new ProductQuantity();
    productData.id = productId;
    productData.quantity = productQuantity;
    this.productsQuantityBeforeEditing.push(productData);
  }

  testear() {
    this.testService.save("hola").subscribe(
      response => {
        console.log(response);
      }
    );
  }

  getTodayDate() {
    const today: Date = new Date();
    const day: number = today.getDate();
    const month: number = today.getMonth() + 1;
    const year: number = today.getFullYear();

    // Aseguramos que el día y el mes tengan siempre 2 dígitos
    const diaStr: string = day.toString().padStart(2, '0');
    const mesStr: string = month.toString().padStart(2, '0');

    return `${diaStr}/${mesStr}/${year}`;
  }

  productosInputChange(value: any) {
    this.searchInput = value.term;
    this.applyFilters();
  }

  applyFilters() {
    if (this.searchInput == "" &&
      this.selectedMarca == 0 &&
      this.selectedRubro == 0 &&
      this.selectedProveedor == 0 &&
      isNullOrUndefined(this.selectedStock))
      this.productosToShow = [];
    else
      this.productosToShow = this.productos.filter(prod =>
        (prod.nametoShow.toLowerCase().includes(this.searchInput.toLowerCase()) || this.searchInput == "")
        && (prod.id_marca == this.selectedMarca || this.selectedMarca == 0)
        && (prod.id_rubro == this.selectedRubro || this.selectedRubro == 0)
        && (prod.id_proveedor == this.selectedProveedor || this.selectedProveedor == 0)
        && (isNullOrUndefined(this.selectedStock) || (this.selectedStock == 0 ? prod.stock == 0 : prod.stock > 0))
      );
  }

  onProductSelected() {
    if (this.selectedSearchProductId) {
      this.cantidad = 1;
      this.descuento = 0;
      const foundProduct = this.productos.find(prod => prod.id == this.selectedSearchProductId);
      this.selectedProduct = { ...foundProduct };
      const foundMarca = this.marcas.find(mar => mar.id == this.selectedProduct.id_marca);
      this.selectedProduct.nametoShow = foundMarca ? foundMarca.nombre + " - " + this.selectedProduct.nombre : this.selectedProduct.nombre;
      this.calculateProductPrice();
    } else {
      this.resetSelectedProductDetails();
    }
  }

  listaChange() {
    const foundLista = this.listas.find(list => list.id == this.selectedCliente.id_lista);
    this.ganancia = foundLista.valor;
    this.calculateProductPrice();
  }

  cantidadChange() {
    this.calculateProductPrice();
  }

  descuentoChange() {
    this.calculateProductPrice();
  }

  typeSelected() {
    if (this.selectedType != 0) {
      switch (+this.selectedType) {
        case 1:
          this.selectedTypeName = "PRESUPUESTO"
          break;
        case 2:
          this.selectedTypeName = "PROFORMA"
          break;
        case 3:
          this.selectedTypeName = "VENTA"
          break;
        case 4:
          this.selectedTypeName = "NOTA DE CRÉDITO"
          break;
        case 5:
          this.selectedTypeName = "NOTA DE DÉBITO"
          break;
        default:
          this.selectedTypeName = ""
      }
    }
  }

  calculateProductPrice() {

    if (this.selectedProduct) {
      const subtotal = this.getCalculatedPrice(this.selectedProduct.precio_costo_final,
        this.cantidad ?? 0, this.ganancia, this.descuento ?? 0);
      this.subtotal = subtotal.toFixed(1)
    }
  }

  getCalculatedPrice(precio: number, cantidad: number, ganancia: number, descuento: number): number {
    const calcPrice = precio;
    const calcGanancia = (calcPrice * ganancia) / 100;
    let price = calcPrice + calcGanancia;
    this.precioXUnidad = price.toFixed(1);
    price = price * cantidad;
    this.precio = price.toFixed(1);

    const calcDcto = (price * descuento) / 100;
    return price - calcDcto;
  }

  removeFilters() {
    this.searchInput = "";
    this.selectedRubro = 0;
    this.selectedMarca = 0;
    this.selectedProveedor = 0;
    this.selectedStock = null;
    this.productosToShow = [];
  }

  resetMarca() {
    this.searchInput = "";
    this.selectedMarca = 0;
    this.applyFilters();
  }

  marcaSelected() {
    this.searchInput = "";
    this.applyFilters();
  }

  resetRubro() {
    this.searchInput = "";
    this.selectedRubro = 0;
    this.applyFilters();
  }

  rubroSelected() {
    this.searchInput = "";
    this.applyFilters();
  }

  resetProveedor() {
    this.searchInput = "";
    this.selectedProveedor = 0;
    this.applyFilters();
  }

  proveedorSelected() {
    this.searchInput = "";
    this.applyFilters();
  }

  resetStock() {
    this.searchInput = "";
    this.selectedStock = null;
    this.applyFilters();
  }

  stockSelected() {
    this.searchInput = "";
    this.applyFilters();
    //this.productosToShow = [];
  }

  resetCliente() {
    this.selectedClienteId = null;
    this.selectedCliente = null;
    this.ganancia = 0;
    this.calculateProductPrice();
  }

  resetVendedor() {
    this.selectedVendedorId = 0;
  }

  clienteSelected() {
    const foundCliente = this.clientes.find(cli => cli.id == this.selectedClienteId);
    this.selectedCliente = { ...foundCliente };
    this.selectedVendedorId = foundCliente.id_vendedor;
    const foundLista = this.listas.find(list => list.id == this.selectedCliente.id_lista);
    this.ganancia = foundLista.valor;
    this.calculateProductPrice();
  }

  cuitChange() {
    if (!isNullOrUndefined(this.selectedCliente.dni_cuit) && this.selectedCliente.dni_cuit.toString().length < 10)
      this.cuitInvalido = true;
    else
      this.cuitInvalido = false;
  }

  razonSocialChange() {
    if (this.selectedCliente.razon_social.length == 0)
      this.razonSocialInvalida = true;
    else
      this.razonSocialInvalida = false;
  }

  addProductClick() {
    let product = new ProductDto();
    product.id = this.selectedProduct.id;
    product.cantidad = this.cantidad ?? 0;
    product.descripcion = this.selectedProduct.nombre;
    product.idLista = this.selectedCliente.id_lista;
    product.descuentoPorcentual = this.descuento ?? 0;
    product.descuentoNominal = 0;
    product.precioUnitarioOriginal = this.selectedProduct.precio_costo_final;
    product.precioUnitario = this.selectedProduct.precio_costo_final;
    product.enOferta = this.selectedProduct.en_oferta;
    product.ofertaAplicada = false;
    product.precioOferta = this.selectedProduct.precio_costo_oferta;
    product.subtotal = +this.subtotal;
    product.subtotalVista = product.subtotal.toFixed(1);
    product.subtotalXUnidadVista = this.precioXUnidad;
    const prodInList = this.productRows.find(p => p.id === product.id);
    if (prodInList)//si el producto ya se encontraba en la lista, sumo cantidades y piso ganancia y descuento con el ultimo valor seleccionado
    {
      prodInList.cantidad += product.cantidad;
      prodInList.descuentoPorcentual = product.descuentoPorcentual;
      prodInList.idLista = product.idLista;
      const list = this.listas.find(list => list.id == prodInList.idLista);
      prodInList.descuentoNominal = product.descuentoNominal;

      prodInList.subtotal = this.getCalculatedPrice(prodInList.precioUnitario, prodInList.cantidad, list.valor, product.descuentoPorcentual);
      prodInList.subtotalVista = prodInList.subtotal.toFixed(1);
    }
    else
      this.productRows.push(product);

    this.productRows = [...this.productRows];

    this.resetProduct();
    this.calculateTotal();

    Swal.fire({
      toast: true,
      title: 'Producto agregado',
      icon: 'success',
      color: 'primary',
      showConfirmButton: false,
      position: 'top-end',
      timer: 2000
    });

    this.searchInput = "";
    this.productSelector.filter(this.searchInput);
    this.productSelector.focus();
  }

  calculateTotal() {
    let total = 0;
    this.productRows.forEach(prod => {
      total += prod.subtotal;

    });
    this.total = total;
    this.totalView = total.toFixed(1);
  }

  resetProduct() {
    this.selectedSearchProductId = null;
    this.resetSelectedProductDetails();
  }

  resetSelectedProductDetails() {
    this.selectedProduct = null;
    this.cantidad = null;
    this.descuento = null;
    this.precio = "";
    this.precioXUnidad = "";
    this.subtotal = "";
  }

  updateProductValues(row: ProductDto) {
    this.calculateSubtotal(row);
    this.productRows = [...this.productRows];
  }

  calculateSubtotal(product: ProductDto) {
    //cantidad * precio unitario
    let price = product.precioUnitario;
    //sumarle ganancia
    let foundList = this.listas.find(list => list.id == product.idLista);
    price = price + ((price * foundList.valor) / 100);
    //restarle descuento porcentaje
    price = price - ((price * product.descuentoPorcentual) / 100);
    //restarle descuento nominal
    price = price - product.descuentoNominal;

    product.subtotalXUnidadVista = price.toFixed(1);
    price = product.cantidad * price;

    product.subtotal = price;
    product.subtotalVista = product.subtotal.toFixed(1);

    this.calculateTotal();
  }

  removeItem(rowIndex: number) {
    this.productRows.splice(rowIndex, 1);
    this.productRows = [...this.productRows];
    this.calculateTotal();

    Swal.fire({
      toast: true,
      title: 'Producto eliminado',
      icon: 'success',
      showConfirmButton: false,
      position: 'top-end',
      timer: 2000
    });
  }

  apllySalePrice(row: ProductDto) {
    row.ofertaAplicada = true;
    row.precioUnitario = row.precioOferta;
    this.calculateSubtotal(row);
    this.productRows = [...this.productRows];
  }

  unApplySalePrice(row: ProductDto) {
    row.ofertaAplicada = false;
    row.precioUnitario = row.precioUnitarioOriginal;
    this.calculateSubtotal(row);
    this.productRows = [...this.productRows];
  }

  openPayment(content: TemplateRef<any>) {
    if (this.allFieldsComplete()) {
      this.modalService.open(content, { scrollable: true }).result.then((result) => {
      }).catch((res) => { });
    }
  }

  save(verificationModal: TemplateRef<any>, paymentForm: PaymentComponent) {
    console.log(paymentForm.payment);
    if (this.paymentFormIsValid(paymentForm)) {
      this.register = new RegisterDto();
      if (this.editing)
        this.register.idVenta = this.registerId;
      this.register.tipo = this.selectedType;
      this.register.idVendedor = this.selectedVendedorId;
      this.register.pago = paymentForm.payment;
      this.register.productos = this.productRows.map(p => {
        p.descripcion = p.descripcion.replace(/"/g, '');
        return p;
      });

      let productIds = new ProductsIdListDto();
      this.productRows.forEach(product => {
        productIds.productos.push(product.id);
      });

      this.register.notas = paymentForm.notes;

      let cliente = new clientDto();
      cliente.id = this.selectedCliente.id;
      cliente.razonSocial = this.selectedCliente.razon_social;
      cliente.cuit = this.selectedCliente.dni_cuit;
      cliente.direccion = this.selectedCliente.direccion;
      cliente.telefono1 = this.selectedCliente.tel_1;
      cliente.telefono2 = this.selectedCliente.tel_2;
      cliente.email = this.selectedCliente.email;
      cliente.idLista = this.selectedCliente.id_lista;
      this.register.cliente = cliente;
      this.register.total = this.total;

      this.loading = true;

      // if (this.selectedType == 2 || this.selectedType == 3) {
      if (this.selectedType == 3) {
        this.productosService.getStockDetail(productIds).subscribe(
          response => {

            let productsUnderStock: ProductDto[] = [];
            let underStockFound = false;
            response.data.forEach(stockDetail => {
              let selectedProduct = this.productRows.find(prod => prod.id == stockDetail.id);

              if (this.productStockIsGoingNegative(selectedProduct.id, selectedProduct.cantidad, stockDetail.stock)) {
                underStockFound = true;
                productsUnderStock.push(selectedProduct);
              }
            });

            if (underStockFound)
              this.confirmationMessage = this.getProductsUnderStockMessage(productsUnderStock, response.data);
            else
              this.confirmationMessage = "¿Seguro que desea realizar el registro?";

            this.modalService.open(verificationModal, { scrollable: true }).result.then((result) => {
              console.log("Modal closed" + result);
            }).catch((res) => { });

            this.loading = false;
          }
        );
      }
      else {
        this.confirmationMessage = "¿Seguro que desea realizar el registro?";

        this.modalService.open(verificationModal, { scrollable: true }).result.then((result) => {
          console.log("Modal closed" + result);
        }).catch((res) => { });

        this.loading = false;
      }
    }
  }

  productStockIsGoingNegative(productId: number, quantity: number, stock: number) {
    if (this.editing) {
      let oldProductQuantity = this.productsQuantityBeforeEditing.find(p => p.id == productId);
      if (oldProductQuantity) {
        let newStock = this.getStockAfterTransaction(stock, quantity, oldProductQuantity.quantity);
        if (newStock < 0)
          return true;
        else
          return false;
      }
      else
        return quantity > stock;
    }
    else
      return quantity > stock;
  }

  getStockAfterTransaction(stock: number, quantity: number, oldQuantity: number) {
    return stock - (quantity - oldQuantity);
  }

  getProductsUnderStockMessage(productsUnderStock: ProductDto[], stockDetail: StockDetail[]): string {
    let message: string = "El/los siguiente/s productos quedarán con stock negativo al realizar el registro.\n\n";

    productsUnderStock.forEach(prod => {
      let stock = stockDetail.find(s => s.id == prod.id);
      let cantidad: number;

      if (this.editing) {
        let oldProductQuantity = this.productsQuantityBeforeEditing.find(p => p.id == prod.id);
        if (oldProductQuantity)
          cantidad = this.getStockAfterTransaction(stock.stock, prod.cantidad, oldProductQuantity.quantity);
      }
      else
        cantidad = stock.stock - prod.cantidad;

      message += "- " + prod.descripcion + "( " + cantidad + ")\n";
    })

    message += "\n¿Desea continuar?";

    return message;
  }

  paymentFormIsValid(paymentForm: PaymentComponent): boolean {

    if (this.editing || this.selectedType != 3)
      return true;

    return true
  }

  allFieldsComplete(): boolean {

    let result = true;
    let errorMessage = "Información faltante:\n";

    if (!this.selectedType) {
      errorMessage += "- Tipo de comprobante\n";
      result = false;
    }
    if (this.selectedVendedorId == 0) {
      errorMessage += "- Vendedor\n";
      result = false;
    }
    //const numericValueCuit = Number();
    if (this.selectedClienteId == 0 || this.selectedCliente.razon_social == "" ||
      this.selectedCliente.email == "" || this.selectedCliente.tel_1 == "" ||
      this.selectedCliente.direccion == "") {
      errorMessage += "- Datos de Cliente\n";
      result = false;
    }

    if (result == false) {
      Swal.fire({
        toast: true,
        title: errorMessage,
        icon: 'error',
        color: 'danger',
        showConfirmButton: false,
        position: 'top-end',
        timer: 4000
      });
    }
    return result;
  }

  confirmSave(optionsModal: TemplateRef<any>) {
    this.loading = true;
    //console.log(JSON.stringify(this.register))
    this.ventasService.save(this.register).subscribe(
      (response) => {
        this.registerId = +response.data;
        //cambiar el register id x el que llegue desde backend
        this.loading = false;
        Swal.fire({
          toast: true,
          title: 'Registro guardado',
          icon: 'success',
          color: 'primary',
          showConfirmButton: false,
          position: 'top-end',
          timer: 2000
        });
        this.showFinalOptions(optionsModal);
      },
      (error) => {

        this.loading = false;
        console.error('Error al guardar el registro', error);
        Swal.fire({
          toast: true,
          title: 'Error al guardar el registro',
          icon: 'error',
          color: 'danger',
          showConfirmButton: false,
          position: 'top-end',
          timer: 2000
        });
      }
    );

    this.modalService.dismissAll();
  }

  showFinalOptions(content: TemplateRef<any>) {
    this.optionsModal = this.modalService.open(content, { scrollable: true });
  }

  resetPage() {
    this.selectedClienteId = 0;
    this.selectedCliente = null;
    this.productsQuantityBeforeEditing = [];
    this.productRows = [];
    this.selectedVendedorId = 0;
    this.selectedType = null;
    this.selectedTypeName = "";
    this.productosToShow = [];
    this.selectedRubro = 0;
    this.selectedMarca = 0;
    this.selectedProveedor = 0;
    this.selectedStock = null;
    this.editing = false;
  }

  print() {
    this.ventasService.getPdfDetail(this.registerId).subscribe(
      response => {
        const blob = response;
        this.downloadFile(blob);
      }
    );
  }

  newClick() {
    this.resetPage();
    this.optionsModal.close();
  }

  editClick() {
    this.resetPage();
    this.editing = true;
    this.optionsModal.close();
    this.loadData(this.registerId);
  }

  downloadFile(file: Blob) {
    let downloadLink = document.createElement('a');
    downloadLink.href = window.URL.createObjectURL(file);
    downloadLink.setAttribute('target', '_blank');
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
  }

  convertToSell() {
    if (this.selectedType == 2)
      this.selectedType = 3;
    else
      this.selectedType = 2;
  }

  filterContent($event: string) {
    const replaceText = $event.replace('a', '');
    //this.content = replaceText
  }
}











