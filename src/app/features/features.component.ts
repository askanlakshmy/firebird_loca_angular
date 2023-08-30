import { Component,OnInit } from '@angular/core';
import { CustomerService } from '../servicesnew/customer.service'

@Component({
  selector: 'app-features',
  templateUrl: './features.component.html',
  styleUrls: ['./features.component.css']
})
export class FeaturesComponent {
  customers:any=''
 constructor(private service:CustomerService){
 
 }
 ngOnInit() {
  this.service.getdata().subscribe((data:any)=>{
    console.log(data);
    this.customers=data.data;
    console.log(this.customers, "testing");
  });
  }
}
