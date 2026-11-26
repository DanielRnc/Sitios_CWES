from Vehiculo import Vehiculo

class Camion(Vehiculo):
    descargando=""
    def descargar(self):
        self.descargando="estoy descargando"

mi_camion=Camion("volvo","FMX")
mi_camion.estado()
mi_camion.descargar()