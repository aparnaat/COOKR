
import pandas as pd
from ast import literal_eval
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.preprocessing import MultiLabelBinarizer
from sklearn.multioutput import MultiOutputClassifier
from sklearn.ensemble import RandomForestClassifier
import joblib


df = pd.read_csv('food_data.csv')
df['Categories'] = df['Categories'].apply(literal_eval)
mlb = MultiLabelBinarizer()
binary = mlb.fit_transform(df['Categories'])
train,test,train_labels,test_labels = train_test_split(df['Item'], binary, test_size=0.1, random_state=42)

vect = CountVectorizer()
X_train = vect.fit_transform(train)
X_test = vect.transform(test)
model = MultiOutputClassifier(RandomForestClassifier(random_state=42))
model.fit(X_train, train_labels)
joblib.dump(model, 'item_categorization_model.joblib')

while(1):
    item = input("Enter a new food item: ").lower()
    if item=="stop":
        break
    else:
        if item not in df['Item'].values:
            vectorized = vect.transform([item])
    
            pred = model.predict(vectorized)
            category = mlb.inverse_transform(pred)
        
            new_row = pd.DataFrame({'Item': [item], 'Categories': [list(category[0])]})
            new_row.to_csv('food_data.csv', mode='a', header=False, index=False)
        
            print(f"'{item}': {list(category[0])}")
        else:
            category = df.loc[df['Item'] == item, 'Categories'].values[0]
            print(f"'{item}' : {category}")
