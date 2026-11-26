import sqlite3

conexion = sqlite3.connect('animales')
cursor = conexion.cursor()

cursor.execute('''
    create table if not exists perros(
    id integer primary key autoincrement,
    raza varchar(10),
    color varchar(10),
    peso integer,
    edad integer,
    sexo varchar(10),
    hijos integer default 0
    )
''')

PERROS_PRUEBA = [
    ('Labrador', 'dorado', '25.0', '4', 'hembra','2'),
    ('Beagle', 'tricolor', '12.0', '3', 'hembra','1'),
    ('Pastor Alemán',' negro y fuego', '30.0', '5', 'hembra','1'),
    ('Caniche','blanco', '6.0', '7', 'hembra','3'),
    ('Border Collie', 'blanco y negro', '18.0', '3', 'hembra','1'),

    ('Cocker', 'marrón', '13.0', '4', 'macho','0'),
    ('Beagle', 'marrón y blanco', '13.0', '6', 'macho','3'),
    ('Schnauzer', 'gris',' 11.0', '5', 'macho','1'),
    ('Rottweiler', 'negro', '45.0', '6', 'macho','2'),
    ('Dálmata', 'blanco y negro', '25.0', '2', 'macho','0'),
]
cursor.executemany('insert into perros values(NULL,?,?,?,?,?,?)', PERROS_PRUEBA)
conexion.commit()
conexion.close()