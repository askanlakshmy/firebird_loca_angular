import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class CustomerService {
postID:any=''
  constructor(private http:HttpClient) { }
  getdata(){
    return this.http.get("https://portal.bestdemo.site/api/liveEmployees/getAllLiveEmployeeStatus");
  }
  employees_data(){
    return this.http.get("https://portal.bestdemo.site/api/liveEmployees/getAllEmployeeMonthlyStatus");
  }
  open_project(){
    return this.http.get("https://portal.bestdemo.site/api/liveEmployees/getAllOpenProjectHours");
  }
}
