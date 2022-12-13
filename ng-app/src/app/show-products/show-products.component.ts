import { Component } from '@angular/core';
import { ApiService } from '../api/api.service';

type ProductType = {
    'id': string,
    'name': string,
    'unit_price': string,
}

@Component({
  selector: 'app-show-products',
  templateUrl: './show-products.component.html',
  styleUrls: ['./show-products.component.css']
})

export class ShowProductsComponent {
    products: ProductType[] = []

    constructor(private api: ApiService) {}

    ngOnInit(): void {
        this.api.get('http://localhost:8082/api/products')
            .subscribe((products: any) => {
                const productsCasted = products.map((product: ProductType) => {
                    return product
                })
                console.log(productsCasted)
                this.products = productsCasted as ProductType[];
            }, (error) => {
                console.error(error)
                alert('Ocorreu um erro ao consultar produtos')
            })
    }

}
