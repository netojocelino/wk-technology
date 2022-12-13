import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';

type CustomerType = {
    'id': string,
    'name': string,
    'cpf': string,
    'email': string,
    'birth_date': string,
    'address_cep': string,
    'address_place': string,
    'address_number': string,
    'address_neighborhood': string,
    'address_complement': string,
    'address_city': string,
}

@Component({
  selector: 'app-show-customers',
  templateUrl: './show-customers.component.html',
  styleUrls: ['./show-customers.component.css']
})
export class ShowCustomersComponent implements OnInit {
    customers: CustomerType[] = []

    constructor(private http: HttpClient) {}

    ngOnInit(): void {
        this.http.get('http://localhost:8082/api/customers')
            .subscribe((customers: any) => {
                const customersCasted = customers.map((customer: CustomerType) => {
                    customer.cpf = customer.cpf.substring(0, 3) + '.xxx.xxx-xx';
                    const birth_date = new Date(customer.birth_date)
                    customer.birth_date = `${birth_date.getDate().toString().padStart(2, '0')}/`
                            +`${birth_date.getMonth().toString().padStart(2, '0')}/${birth_date.getFullYear()}`

                    return customer
                })
                console.log(customers)
                this.customers = customers as CustomerType[];
            }, (error) => {
                console.error(error)
                alert('Ocorreu um erro ao consultar clientes')
            })
    }
}
