import sqlite3

conexion = sqlite3.connect('animales')
cursor = conexion.cursor()

def obtenerTodos():
    cursor.execute('select * from perros')
    for fila in cursor.fetchall():
        print(fila[0],fila[1],'-raza',fila[2],'-color',fila[3],'-peso',fila[4],'-edad'
            ,fila[5],'-sexo',fila[6],'-hijos')