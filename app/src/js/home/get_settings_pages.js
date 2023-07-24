function menu_get(page) {

     const activeItem = document.querySelector('.settings-menu .text-purple');
     if (activeItem) {
         activeItem.classList.remove('text-purple');
     }

     const links = document.getElementsByClassName(page);
     if (links.length > 0) {
         links[0].querySelector('li').classList.add('text-purple');
     }

     fetch('/src/php/home/pages/account_settings/' + page + '.php')
         .then(response => response.text())
         .then(content => {
             document.querySelector('.settings-content').innerHTML = content;
         })
         .catch(error => console.error('Error fetching content:', error));
}