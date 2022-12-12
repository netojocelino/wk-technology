import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AddCustomersComponent } from './add-customers/add-customers.component';
import { AddProductComponent } from './add-product/add-product.component';

import { HomeComponent } from './home/home.component';

const routes: Routes = [
    { path: '', component: HomeComponent },
    { path: 'add-customer', component: AddCustomersComponent },
    { path: 'add-product', component: AddProductComponent },

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
