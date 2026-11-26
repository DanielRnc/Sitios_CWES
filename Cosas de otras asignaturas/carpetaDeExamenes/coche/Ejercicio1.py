class Coche():
    def __init__(self):
        self.largoCarroceria=400
        self.anchCarroceria=200
        self.ruedas=4
        self.color='gris'
        self.enmarcha=False

    def arrancar(self):
        self.enmarcha=True

    def estado(self):
        if(self.enmarcha):
            return "El coche está en marcha"
        else:
            return "El coche está parado"


miCoche = Coche()

print("Largo de la carroceria", miCoche.largoCarroceria)
print("El coche tiene ",miCoche.ruedas," ruedas")

miCoche.arrancar()
print(miCoche.estado())

class Camion(Vehiculo):
    pass

miCamion= Camion("Volvo","FMX")
miCamion.estado()
    