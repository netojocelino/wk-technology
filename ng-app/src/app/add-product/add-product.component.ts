import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';


type FormInput = 'name' | 'unit_price';

@Component({
  selector: 'app-add-product',
  templateUrl: './add-product.component.html',
  styleUrls: ['./add-product.component.css']
})

export class AddProductComponent {
    title = 'Adicionar Produto'
    formRecords: Record<FormInput, any> = {
        'name': '',
        'unit_price': '',
    }

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

    update(inputHTML: any, value: string) {
        const key: FormInput = inputHTML.name;
        if (key === undefined) return;

        this.formRecords[key] = value
    }

    postProduct () {
        this.http.post(`http://localhost:8082/api/product`, this.formRecords)
        .subscribe( (response: any) => {
            console.log(response)
            alert(`Produto ${response.name} cadastrado com sucesso`)
        }, (error) => {
            console.error('Ocorreu um erro ao cadastrar produto');
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
      this.postProduct()

    }
}
