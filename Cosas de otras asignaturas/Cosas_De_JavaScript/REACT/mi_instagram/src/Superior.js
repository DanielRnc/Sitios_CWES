function ParteIzquierda() {
    return (
        <div className="zona-izquierda">
        </div>
    );
}

function ParteDerecha() {
    return (
        <div className="zona-derecha">
        </div>
    );
}

function Superior() {
    return (
        <div className="componente-superior">
            <ParteIzquierda />
            <ParteDerecha />
        </div>
    );
}

export default Superior;
