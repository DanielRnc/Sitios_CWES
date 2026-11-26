from Perro import Perro


padre = Perro('perro1', 'color1', 9, 4, 'macho')
madre = Perro('perro2', 'color2', 10, 6, 'hembra')
PerroAparte = Perro('perro3', 'color3', 7, 3, 'macho')

padre.guardar()
madre.guardar()

cachorro = padre.cruzar(madre)


