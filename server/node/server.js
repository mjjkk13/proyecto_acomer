// server/node/server.js
const express = require('express');
const cors = require('cors');
const { enviarCredenciales } = require('./services/mailer');
const path = require('path');
require('dotenv').config({ path: path.resolve(__dirname, '.env') });

const app = express();

console.log("[DEBUG] Email user:", process.env.EMAIL_USER); 
console.log("[DEBUG] Email password:", process.env.EMAIL_PASSWORD); 


app.use(cors({
  origin: 'http://localhost:5173', // Ajusta al puerto de tu frontend
  methods: ['POST'],
  credentials: true
}));
app.use(express.json());

// Única ruta necesaria
app.post('/enviar-credenciales', async (req, res) => {
  const { destinatario, usuario, contrasena } = req.body;
  
  // Validación básica
  if (!destinatario || !usuario || !contrasena) {
    return res.status(400).json({
      success: false,
      message: 'Faltan campos requeridos'
    });
  }

  const resultado = await enviarCredenciales(destinatario, usuario, contrasena);
  
  if (resultado.success) {
    res.json({ success: true, message: 'Correo enviado exitosamente' });
  } else {
    res.status(500).json({
      success: false,
      message: 'Error al enviar correo',
      error: resultado.error
    });
  }
});

// Iniciar servidor
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`✅ Servidor de correos en http://localhost:${PORT}`);
});