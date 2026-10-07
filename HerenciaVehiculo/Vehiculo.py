class Vehiculo():
    def __init__(self,marca,modelo):
        self.marca=marca
        self.modelo=modelo
        self.enmarcha=False
        self.acelera=False
        self.frena=False 

    def arrancar(self):
        self.enmarcha=True

    def acelerar(self):
        self.acelera=True

    def frenar(self):
        self.frena=True

    def estado(self):
        print("Marca: ",self.marca," Modelo: ",self.modelo, " En marcha: ", self.enmarcha, " Acelerando:", self.acelera, " Frenando: ",self.frena)