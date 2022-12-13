import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api/api.service';


type SaleType = {
    id: string
    customer_name: string
    items_name: string
    total_price: string
}

type SaleInputType = {
    id: string
    customer: {
        id: string
        name: string
    }
    items: {
        id: string
        total_price: string
        total_items: number

        product: {
            id: string
            name: string
            unit_price: string
        }
    }[]
    total_price: string
}

@Component({
  selector: 'app-show-sales',
  templateUrl: './show-sales.component.html',
  styleUrls: ['./show-sales.component.css']
})

export class ShowSalesComponent implements OnInit {
    title = 'Listar Vendas'
    sales: SaleType[] = []

    constructor(private api: ApiService) {}

    ngOnInit(): void {
        this.api.get('http://localhost:8082/api/order/sale')
            .subscribe((sales: any) => {
                const salesCasted = sales.map((sale: SaleInputType) => {
                    const items: any[] = sale.items?.map((item: any) => item.product.name)
                    const items_name = (items.length > 0) ? items?.join(', ') : 'Nenhum item na venda'

                    const outputSale: SaleType = {
                        id: sale.id,
                        customer_name: sale.customer.name,
                        items_name,
                        total_price: sale.total_price
                    }
                    return outputSale
                })
                this.sales = salesCasted as SaleType[];
            }, (error) => {
                console.error(error)
                alert('Ocorreu um erro ao consultar Vendas')
            })

    }
}
