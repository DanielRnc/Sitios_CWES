import sqlite3

conexion = sqlite3.connect('animales')
cursor = conexion.cursor()
cursor.execute('select * from perros')

fichero = open('perros.txt', 'w', encoding='utf-8')

'''
    no me da tiempo a terminar esto
'''


