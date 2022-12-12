import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { HomeComponent } from './home/home.component';
import { AddCustomersComponent } from './add-customers/add-customers.component';
import { AddProductComponent } from './add-product/add-product.component';
import { ShowCustomersComponent } from './show-customers/show-customers.component';

const routes: Routes = [
    { path: '', component: HomeComponent },
    { path: 'add-customer', component: AddCustomersComponent },
    { path: 'add-product', component: AddProductComponent },
    { path: 'show-customers', component: ShowCustomersComponent },

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
