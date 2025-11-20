import React from 'react';
import { AppRegistry, SafeAreaView } from 'react-native';
import CardStack from './components/CardStack';

const sample = [
  { id: 1, name: 'Alice', age: 25, location: 'Tokyo', pictures: [{url: 'https://placekitten.com/400/400'}] },
  { id: 2, name: 'Bob', age: 28, location: 'Osaka', pictures: [{url: 'https://placekitten.com/401/401'}] }
];

export default function App() {
  return (
    <SafeAreaView style={{flex:1}}>
      <CardStack data={sample} />
    </SafeAreaView>
  );
}

AppRegistry.registerComponent('TinderApp', () => App);
