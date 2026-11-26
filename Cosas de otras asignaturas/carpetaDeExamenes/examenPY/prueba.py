import mysql.connector

conexion = mysql.connector.connect(
    user='root',
    password='',
    host='127.0.0.1',
    port='41064'
)
print("Conectado")
conexion.close()