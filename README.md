<p align="center">
  <a href="https://yoshi.kulayuki.com/" target="_blank">
    <img src="./public/assets/images/brand_icons/512x512.png" width="200" style="border-radius: 1rem" alt="Yukita">
  </a>
</p>

## About Yukita

Yukita adalah aplikasi pencari temen main bareng saat bermain game online.


## How to Install ?
- Open your terminal
- Change directory you want
- Type ```git clone --branch main https://gitlab.com/indo-hp/yukita-app/yukita-backend ./yukita-backend``` in terminal
- After that, type ```cd yukita-backend``` to enter the ```yukita-backend``` directory

## How to Run ?
- Create a database whatever the name
- Type ```cp .env.example .env``` in terminal
- Adjust the ```DB_DATABASE``` according the database you created 
- Type ```php artisan key:generate``` in terminal
- Type ```php artisan migrate -seed``` in terminal
- After that serve the project with ```php artisan serve``` in your terminal
- Voila! your project is ready to use