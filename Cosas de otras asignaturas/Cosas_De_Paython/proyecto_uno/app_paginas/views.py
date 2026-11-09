from django.http import HttpResponse

def inicio(request):
    return HttpResponse("Ésta es la página de inicio")

def pagina1(request):
    return HttpResponse("Ésta es la página 1.")

def pagina2(request):
    return HttpResponse("Ésta es la página 2.")