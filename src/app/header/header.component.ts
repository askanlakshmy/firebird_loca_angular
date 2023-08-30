import { Component,Input } from '@angular/core';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent {
  isMenuOpen: boolean = false;
  isClose:boolean=false;
  is_open_close:boolean=false;
  toggleMenu() {
    this.isMenuOpen = !this.isMenuOpen;
    this.isClose= !this.isClose;
    this.is_open_close= !this.is_open_close;
  }
}
