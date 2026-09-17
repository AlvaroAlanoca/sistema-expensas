from fastapi import FastAPI
from pydantic import BaseModel
import joblib
import pandas as pd

# Inicializar la API
app = FastAPI(title="API Predictiva de Expensas")

# Cargar el "cerebro" (nuestro modelo entrenado)
modelo = joblib.load('modelo_morosidad.pkl')

# Definir la estructura de datos que Laravel nos enviará
class DatosExpensa(BaseModel):
    monto: float
    piso: int
    tipo_propiedad: str

@app.post("/predecir")
def predecir_morosidad(datos: DatosExpensa):
    # 1. Transformar los datos para que el modelo los entienda (One-Hot Encoding manual)
    tipo_local = 1 if datos.tipo_propiedad == 'local' else 0
    tipo_oficina = 1 if datos.tipo_propiedad == 'oficina' else 0

    # Crear el DataFrame con los datos recibidos
    df_pred = pd.DataFrame([{
        'monto': datos.monto,
        'piso': datos.piso,
        'tipo_propiedad_local': tipo_local,
        'tipo_propiedad_oficina': tipo_oficina
    }])

    # 2. Asegurar que las columnas coincidan exactamente con las del entrenamiento
    columnas_entrenamiento = modelo.feature_names_in_
    for col in columnas_entrenamiento:
        if col not in df_pred.columns:
            df_pred[col] = 0
            
    df_pred = df_pred[columnas_entrenamiento]

    # 3. Hacer la predicción
    # predict() devuelve 0 o 1
    prediccion = modelo.predict(df_pred)[0]
    
    # predict_proba() devuelve los porcentajes [probabilidad_0, probabilidad_1]
    probabilidad = modelo.predict_proba(df_pred)[0][1] 

    return {
        "riesgo_alto": bool(prediccion == 1),
        "probabilidad_atraso_porcentaje": round(probabilidad * 100, 2),
        "mensaje": "Alto riesgo de mora" if prediccion == 1 else "Bajo riesgo, pago probable a tiempo"
    }