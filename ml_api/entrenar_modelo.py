import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
import joblib

# 1. Cargar los datos extraídos de Laravel
df = pd.read_csv('dataset_expensas.csv')

# 2. Convertir texto a números (El modelo no entiende la palabra "departamento", necesita variables binarias)
df = pd.get_dummies(df, columns=['tipo_propiedad'], drop_first=True)

# 3. Separar las características a analizar (X) y el objetivo a predecir (y)
X = df.drop('es_moroso', axis=1)
y = df['es_moroso']

# 4. Dividir en datos de entrenamiento (80%) y prueba (20%)
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# 5. Crear y entrenar el algoritmo (Random Forest)
modelo = RandomForestClassifier(n_estimators=100, random_state=42)
modelo.fit(X_train, y_train)

# 6. Evaluar y guardar el modelo entrenado
precision = modelo.score(X_test, y_test)
print(f"Precisión del modelo: {precision * 100:.2f}%")

# Guardar el "cerebro" entrenado en un archivo físico
joblib.dump(modelo, 'modelo_morosidad.pkl')
print("Modelo guardado exitosamente como 'modelo_morosidad.pkl'")