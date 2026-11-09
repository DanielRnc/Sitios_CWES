import React from 'react';
import './App.css';

function App() {
  return (
    <div className="content-app">
      <header className="main-header">
        <div className="header-links-left">
          <a href="#about" className="header-link">Acerca de</a>
          <a href="#store" className="header-link">Tienda</a>
        </div>
        <div className="header-links-right">
          <a href="#gmail" className="header-link">Gmail</a>
          <a href="#images" className="header-link">Imagenes</a>
        </div>
      </header>
      <div className='logotipo'>
        <span className='letra1'>G</span>
        <span className='letra2'>o</span>
        <span className='letra3'>o</span>
        <span className='letra4'>g</span>
        <span className='letra5'>l</span>
        <span className='letra6'>e</span>
      </div>
      <input type="text" placeholder='Que quieres buscar...'
        className='centered-input'/>
      <div className="button-container">
        <button className="action-button">Buscar</button>
        <button className="action-button">Voy a tener suerte</button>
      </div>
    </div>
    
  );
}

export default App;
