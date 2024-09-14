# AQUI SE COLOCAN LOS ARCHIVOS DE php_basesDatos en formato de clases con funciones

Ej
```
<?php
class User extends Model {
    public function getUsers() {
        $this->db->query("SELECT * FROM users");
        return $this->db->resultSet();
    }

    public function getUserById($id) {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}

```