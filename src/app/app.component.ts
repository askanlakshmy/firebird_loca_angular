import { Component,OnInit } from '@angular/core';
import { CustomerService } from './servicesnew/customer.service'
import {MatIconModule} from '@angular/material/icon';
import {MatTabsModule} from '@angular/material/tabs';
import {MatTableModule} from '@angular/material/table';
import { DatePipe } from '@angular/common';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [DatePipe]
})
export class AppComponent {
  isToggled: boolean = true;

  toggle() {
    this.isToggled = !this.isToggled;
  }
  title = 'firdbird';
  customers:any=''
  employee:any=''
  review:any=''
  review_new:any=''
  productivity:any=''
  prv:any=''
  postId?:any=''
  date:any='';
  name:any='';
  showLoader: boolean = true;
 constructor(private service:CustomerService,private http: HttpClient,public datepipe: DatePipe){
 
 }
 ngOnInit():void {
  setTimeout(() => {
    // Your initialization logic here
    var d = new Date();
    var stat = d.toLocaleDateString("sv-SE");
    d.setMonth(d.getMonth() - 3);
    var new_x1 = d.toLocaleDateString("sv-SE");
    this.postmethod(stat, new_x1);
    // Hide the loader after the initialization is complete
    this.showLoader = false;
  }, 2000);
 

  this.service.getdata().subscribe((data: any) => {
    this.customers = data.data;
  });
  this.service.employees_data().subscribe((data: any) => {
    this.employee = data.data;
  });
  this.service.open_project().subscribe((data: any) => {
    this.productivity = data.data;
    console.log(this.productivity,"new_product");
    // for (let i = 0; i < this.productivity.length; i++) {
    // }
  });
  }
  
  postmethod(new_x1: any, stat: any) {
    let body = {
      login_user_id: 1,
      month_start: stat,
      month_end: new_x1
    }
    console.log(body, "body_tag")
    this.http.post('https://portal.bestdemo.site/api/getMonthlyFilterManageCredit/manage_credit', body).subscribe((data: any) => {
      this.review = data.data;
    });
  }
  quarterly(): void {
    var d = new Date();
    var stat = d.toLocaleDateString("sv-SE")
    d.setMonth(d.getMonth() + 3);
    var new_x1 = d.toLocaleDateString("sv-SE");
    console.log(new_x1, "new_date--");
    this.postmethod(stat, new_x1)
  }    
  currentClick():void{
    var d = new Date();
    var stat = d.toLocaleDateString("sv-SE");
    d.setMonth(d.getMonth() - 3);
    var new_x1 = d.toLocaleDateString("sv-SE");
    this.postmethod(stat, new_x1);
  }
}
