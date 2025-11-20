import React from 'react';
import { View, Text, Image, StyleSheet, Dimensions } from 'react-native';
import Swiper from 'react-native-deck-swiper';

const SCREEN = Dimensions.get('window');

export default function CardStack({ data = [] }) {
  const renderCard = (card) => (
    <View style={styles.card} key={card.id}>
      <Image source={{ uri: card.pictures?.[0]?.url }} style={styles.image} />
      <View style={styles.info}>
        <Text style={styles.name}>{card.name}, {card.age}</Text>
        <Text style={styles.location}>{card.location}</Text>
      </View>
    </View>
  );

  return (
    <View style={styles.container}>
      <Swiper
        cards={data}
        renderCard={renderCard}
        onSwipedRight={(index) => console.log('liked', data[index].id)}
        onSwipedLeft={(index) => console.log('disliked', data[index].id)}
        cardIndex={0}
        backgroundColor={'transparent'}
        stackSize={3}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, alignItems: 'center', justifyContent: 'center' },
  card: {
    width: SCREEN.width * 0.9,
    height: SCREEN.height * 0.7,
    borderRadius: 10,
    overflow: 'hidden',
    backgroundColor: '#fff',
    elevation: 3,
  },
  image: { width: '100%', height: '80%' },
  info: { padding: 12 },
  name: { fontSize: 20, fontWeight: '700' },
  location: { fontSize: 14, color: '#666' }
});
