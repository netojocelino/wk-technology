import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';

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

    constructor(private http: HttpClient) {}

    ngOnInit(): void {
        this.http.get('http://localhost:8082/api/products')
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
