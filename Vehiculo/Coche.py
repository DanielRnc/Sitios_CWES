class Coche():
   
    def __init__(self):
        self.largoCarroceria=400
        self.anchoCarroceria=200
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
        

miCoche=Coche()

print(miCoche.arrancar(True))
miCoche.estado()

    









