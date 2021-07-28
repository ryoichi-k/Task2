//memo no relation of Task01

const btn = document.querySelector('#btn');
const h1 = document.querySelector('h1');

function hello() {
    alert('hello');
    this.style.color = "red";
};
function changeColor() {
    //this.style.color = "red";
    h1.style.color = "red";
};
btn.addEventListener('click', hello);
btn.addEventListener('click', changeColor);
//btn.removeEventListener('click', hello);