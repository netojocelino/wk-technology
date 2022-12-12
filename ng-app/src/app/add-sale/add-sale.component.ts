import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';

type CustomerType = {
    id: String
    name: String
}
type ProductType = {
    id: String
    name: String
    unit_price: number
    qtd: number
}
type ItemType = {
    'product_id': String
    'product_name': String
    'product_price': String
    'product_qtd': number
}

type FormInput = 'sale_date' | 'customer_id' | 'products' | 'product_id';

@Component({
  selector: 'app-add-sale',
  templateUrl: './add-sale.component.html',
  styleUrls: ['./add-sale.component.css']
})

export class AddSaleComponent {
    title = "Adicionar Venda"

    formRecords: Record<FormInput, any> = {
        sale_date: '',
        customer_id: null,
        products: [
            { id: '1', name: '1st Product', unit_price: 124.12, qtd: 0 },
            { id: '2', name: '2nd Product', unit_price: 24.22, qtd: 0 },
            { id: '3', name: 'Third Product', unit_price: 14.55, qtd: 0 },
            { id: '4', name: '4th Product', unit_price: 40.27, qtd: 0 },
            { id: '5', name: '5th Product', unit_price: 400.96, qtd: 0 },
        ] as ProductType[],
        product_id: null,
    }

    // id, name
    customers: CustomerType[] = [
        { id: '1', name: 'First Customer' },
        { id: '2', name: 'Second Customer' },
        { id: '3', name: 'Third Customer' },
        { id: '4', name: '4th Customer' },
        { id: '5', name: '5th Customer' },
    ]
    products_id: number[] = []

    constructor(private http: HttpClient) {}

    getCustomerById(customer_id: string) {
        if (customer_id === undefined) return;
        const customer = this.customers.find((customer: CustomerType) => customer.id == customer_id)

        return customer?.name
    }

    getProductIndexById (product_id: string | number) {
        const index: number = this.formRecords.products
                .findIndex((item: ProductType) => item.id == product_id)
        return index
    }

    getTotal () {
        let total = 0;
        this.products_id
            .forEach((index: number) => {
                const product_index = this.getProductIndexById(index);
                const item = this.formRecords.products[product_index]
                total += (item.unit_price * item.qtd) + total;
            });
        return total.toFixed(2)
    }

    getSelectedProducts() {
        const output: ProductType[]  = []

        this.products_id
            .forEach((index: number) => {
                const product_index = this.getProductIndexById(index)
                if (product_index < 0) return;
                const product = this.formRecords.products[product_index]

                output.push(product);
            });

        return output;
    }

    addProductToChart () {
        if (!this.products_id.includes(this.formRecords.product_id)) {
            this.products_id.push(this.formRecords.product_id)
        }

        const product_index = this.getProductIndexById(this.formRecords.product_id)

        this.formRecords.products[product_index].qtd ++;
    }

    update(inputHTML: any) {
        const key: FormInput = inputHTML.name;
        if (key === undefined) return;

        this.formRecords[key] = inputHTML.value
    }

    subQtd(index: number) {
        if (this.formRecords.products[index].qtd > 0) {
          this.formRecords.products[index].qtd--;
        }
        if (this.formRecords.products[index].qtd < 1) {
            this.formRecords.products.splice(index, 1);
        }
    }

    addQtd(index: number) {
        this.formRecords.products[index].qtd++;
    }



    postSale (data: any) {
        this.http.post(`http://localhost:8082/api/order/sale`, data)
        .subscribe( (response: any) => {
            console.log(response)
            alert(`Venda cadastrado com sucesso`)
        }, (error) => {
            console.error('Ocorreu um erro ao cadastrar venda', error)
            if (error.status == 422) {
                alert(error.error.message)
            }
        })
    }

    save() {
        if (this.formRecords.customer_id == null || `${this.formRecords.customer_id}`.toString().length < 1 ) {
            alert('É necessário escolher um cliente.')
            return;
        }
        const products = this.formRecords.products
            .filter((product: ProductType) => product.qtd > 0)
            .map((item: ProductType) => {
                const product = {
                    product_id: item.id,
                    unit_price: item.unit_price,
                }
                return product
            })

        if (products.length < 1) {
            alert('Nenhum produto selecionado')
            return;
        }

        const now = new Date()
        const formatDate = (date: any) => date.toString().padStart(2, '0')
        const sale_date = `${now.getFullYear()}-${formatDate(now.getMonth())}-${formatDate(now.getDate())}`


        const data = {
            sale_date,
            customer_id: this.formRecords.customer_id,
            items: products
        }

        this.postSale(data)
        console.log(data)
    }

}
