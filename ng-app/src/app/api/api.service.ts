import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  readonly baseUrl = 'http://localhost:8082/api'

  constructor (private http: HttpClient) {}

  get (url: string, options?: any) {
    return this.http.get(`${this.baseUrl}${url}`, options)
  }

  post (url: string, body: any, options?: any) {
    return this.http.post(`${this.baseUrl}${url}`, body, options)
  }

}
