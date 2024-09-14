# AQUI SE SE CARGAN LAS VISTAS Y EL MODELO EN FORMA DE CLASES

EJ
```
<?php
class HomeController extends Controller {
    public function index() {
        $userModel = $this->model('User');
        $users = $userModel->getUsers();
        $this->view('home', ['title' => 'Usuarios', 'users' => $users]);
    }
}
```