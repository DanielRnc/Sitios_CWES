class Coche():
   
    def __init__(self):
        self.__largoCarroceria=400
        self.__anchoCarroceria=200
        self.__ruedas=4
        self.__color='gris'
        self.__enmarcha=False

    def arrancar(self, arrancamos):     
        self.__enmarcha=arrancamos
        valor=" ";

        if(self.__enmarcha):
            check=self.auto_check()

        if(self.__enmarcha and check):
            valor= "El coche está en marcha"

        elif(self.__enmarcha and check==False):
            valor="Chequeo no superado. Arranque no posible"
        else:
            valor="El coche está parado"

        return valor;

    
    def estado(self):
        if(self.__enmarcha):
            return "El coche está en marcha"
        else:
            return "El coche está parado"

    def auto_check(self):
        print("Vamos a realizar el chequeo")

        gasolina="ok"
        aceite="ok"
        puertas="cerradas"
        aceite="ok"

        if(gasolina=="ok" and aceite=="ok" and puertas=="cerradas" and aceite=="ok"):
            return True
        else:
            return False

miCoche=Coche()
print(miCoche.arrancar(True))
miCoche.estado()
print(miCoche.auto_check())

    









