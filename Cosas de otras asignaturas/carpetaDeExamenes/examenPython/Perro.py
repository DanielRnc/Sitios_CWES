import sqlite3

class Perro:

    def __init__(self,raza,color,peso,edad,sexo,hijos=0, id=None):
        self.id = id
        self.raza = raza
        self.color = color
        self.peso = peso
        self.edad = edad
        self.sexo = sexo
        self.hijos = hijos 

    def guardar(self):
        conexion = sqlite3.connect('animales')
        cursor = conexion.cursor()
        if self.id is None:
            cursor.execute('insert into perros (raza, color, peso, edad, sexo, hijos) values (?,?,?,?,?,?)',
                           (self.raza, self.color, self.peso, self.edad, self.sexo, self.hijos))
            self.id = cursor.lastrowid
        else:
            cursor.execute('update perros set hijos = ? where id = ?', (self.hijos, self.id))
        conexion.commit()
        conexion.close()

    def cruzar(self, UnPerro):
        if self.sexo == UnPerro.sexo:
            return None

        if self.sexo == 'hembra':
            madre = self
            padre = UnPerro
        else:
            padre = self
            madre = UnPerro

        cachorro = Perro(madre.raza, padre.color, 1, 0, 'hembra', 0)
        cachorro.guardar()

        madre.hijos += 1
        padre.hijos += 1

        madre.guardar()
        padre.guardar()

        return cachorro

    def getId(self):
        return self.id