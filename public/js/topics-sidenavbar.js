// SideNav bar hide
function pcsh1() {
    var x = document.getElementById("pc1");
    if (x.style.display == "none") {
      x.style.display = "block";
      document.getElementById("toogle-topics-navbar").style.marginLeft = '0px';
    } else {
      x.style.display = "none";
      document.getElementById("toogle-topics-navbar").style.marginLeft = '-250px';
    }
}
  
const navLinks = document.querySelectorAll('.nav-item')
const menuToggle = document.getElementById('navbarSupportedContent')
const bsCollapse = new bootstrap.Collapse(menuToggle)
navLinks.forEach((l) => {
    l.addEventListener('click', () => { bsCollapse.toggle() })
})