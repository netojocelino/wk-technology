import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';


type FormInput = 'name' | 'cpf' | 'email' | 'birth_date'
    | 'address_cep' | 'address_place' | 'address_number'
    | 'address_neighborhood' | 'address_complement' | 'address_city'


@Component({
  selector: 'app-add-customers',
  templateUrl: './add-customers.component.html',
  styleUrls: ['./add-customers.component.css'],
})

export class AddCustomersComponent {
  formRecords: Record<FormInput, any> = {
    'name': '',
    'cpf': '',
    'email': '',
    'birth_date': '',
    'address_cep': '',
    'address_place': '',
    'address_number': '',
    'address_neighborhood': '',
    'address_complement': '',
    'address_city': '',
  }

  title = 'Adicionar Clientes';

  constructor (private http: HttpClient) {}

  validate () {
    const inputsKey = Object.keys(this.formRecords);
    const inputsEmpty = inputsKey.filter(
            ( record: string ) => {
                const input = this.formRecords[record as FormInput];
                const trimmedInput = input.toString().trim()
                return trimmedInput.length < 1
            }
    )

    return inputsEmpty
  }

  update (inputHTML: any, value: string) {
    const key: FormInput = inputHTML.name;
    if (key === undefined) return;

    this.formRecords[key] = value
  }

  postCustomer () {
    this.http.post(`http://localhost:8082/api/customer`, this.formRecords)
        .subscribe( (response: any) => {
            console.log(response)
            alert(`Cliente ${response.name} cadastrado com sucesso`)
        }, (error) => {
            console.error('Ocorreu um erro ao cadastrar cliente');
            console.error(error)
            if (error.status == 422) {
              alert(error.error.message)
            }
        })
  }

  save () {
    const invalidInputs = this.validate()
    if (invalidInputs.length > 0) {
        window.alert(`Campos ${invalidInputs.join(', ')} precisam ser preenchidos. `)
        return
    }
    this.postCustomer()

  }
}
