/**
 * RedblackTree.h
 */

#pragma once

#include <iostream>

using namespace std;

#define null nullptr

namespace Algorithm{

/*!@class RedBlackTreeAbstract
 * @brief Red Black Tree implementation. Please use RedBlackTree instead.
 *
 * This is an abstract implementation so it can afford performance loss
 * due to virtual dispatch by allowing it to be extended for reasons like
 * unit-testing or other concrete impelementation. For an official concrete
 * implementation use RedBlackTree instead.
 *
 * This implementation of RedBlackTree is a 1-1 correspondence of 2-3 Trees.
 * Insert, Delete, and Access are guaranteed to be O(log(n)).
 *
 * @see RedBlackTree
 */
template<typename K, typename V>
class RedBlackTreeAbstract{
 protected:
  using Color = bool;
  static const bool RED = true;
  static const bool BLACK = false;

  /*!@class Node
   * @brief A Red Black Tree node, hence it can
   *        only be used in a Red Black Tree.
   */
  template<typename S, typename T>
  struct Node{
    Node(const S& k, const T& v, size_t s, Color c) :
        key(k), val(v), size(s), color(c){}
    S key;
    T val;
    Node* left = null;
    Node* right = null;
    size_t size = 1;
    Color color = RED;
  };

 public:
  /**
   * Delete every single element.
   */
  virtual ~RedBlackTreeAbstract(){
    while(!isEmpty()){
      deleteMin();
    }
  }

  /**
   * @param key Key of the value to be inserted in the tree.
   * @param val
   */
  virtual void put(const K& key, const V& val){
    _root = _put(_root, key, val);
    _root->color = BLACK;
  }

  /**
   * Delete key with minimum value.
   */
  virtual void deleteMin(){
    if(!_getColor(_root->left) == BLACK &&
       !_getColor(_root->right) == BLACK){
      _root->color = RED;
    }

    _root = _deleteMin(_root);
    if (_size(_root) > 0){
      _root->color = BLACK;
    }
  }

  /**
   * Delete key with maximum value.
   */
  virtual void deleteMax(){
    // Base case: no elements.
    if(isEmpty()) return;
    
    if(!_getColor(_root->left) == BLACK &&
       !_getColor(_root->right) == BLACK){
      _root->color = RED;
    }
    
    _root = _deleteMax(_root);
    if (_size(_root) > 0){
      _root->color = BLACK;
    }
  }

  /**
   * @param key of the value to be erase.
   */
  virtual void erase(const K& key){
    // Base case: no elements.
    if(isEmpty()) return;
    
    if(_getColor(_root->left) == BLACK &&
       _getColor(_root->right) == BLACK){
      _root->color = RED;
    }
    
    _root = _erase(_root, key);
    if(!isEmpty()){
      _root->color = BLACK;
    }
  }

  /**
   * @return size of the Tree.
   */
  size_t size() const{
    return _size(_root);
  }

  /**
   * @return true if empty.
   */
  bool isEmpty() const{
    return size() == 0;
  }

  /**
   * @param key of the value to be retrieved.
   * @return value associated with the key.
   * @throws Exception if value is not found.
   */
  virtual V& get(const K& key){
    auto r = _get(_root, key);
    if(r != null){
      return r->val;
    }else{
      //throw exception();
    }
  }

  /**
   * @param key of the value to be retrieved.
   * @return value associated with the key.
   * @throws Exception if value is not found.
   */
  virtual const V& get(const K& key) const{
    return get(key);
  }

  /**
   * @param key of the value to be retrieved.
   * @return value associated with the key.
   * @throws Exception if value is not found.
   */
  virtual V& operator[](const K& key){
    return get(key);
  }

  /**
   * @param key of the value to be retrieved.
   * @return value associated with the key.
   * @throws Exception if value is not found.
   */
  virtual const V& operator[](const K& key) const{
    return get(key);
  }

  /**
   * @return value of the minimum key.
   */
  virtual V& getMin(){
    return _min(_root)->val;
  }

  /**
   * @return value of the minimum key.
   */
  virtual const V& getMin() const{
    return getMin();
  }

  /**
   * @return value of the maximum key.
   */
  virtual V& getMax(){
    return _max(_root)->val;
  }

  /**
   * @return value of the maximum key.
   */
  virtual const V& getMax() const{
    return getMax();
  }

 public:
  /**
   * @return height of the tree.
   */
  virtual size_t getHeight() const{
    return _height(_root);
  }

  /**
   * @param key 
   * @return height of key in the tree.
   */
  virtual size_t getHeight(const K& key) const{
    return _height(_root, key);
  }

  /**
   * @param key
   * @return true if the key is a leaf.
   */
  virtual bool isLeaf(const K& key) const{
    if (_get(_root, key) == null) return false;
    return (_get(_root, key)->left == null || _get(_root, key)->right == null);
  }
  
 protected:
  // Helper functions.
  Node<K, V>* _put(Node<K, V>* node, const K& key, const V& val){
    // If root is null, then return a new root.
    if(node == null) return new Node<K, V>(key, val, 1, RED);
            
    if(key < node->key){
      node->left = _put(node->left, key, val);      
    }else if(key > node->key){
      node->right = _put(node->right, key, val);      
    }else{
      node->val = val;
    }

    // Traversing upwards.
    if(_getColor(node->left) == BLACK && _getColor(node->right) == RED){
      node = _rotateLeft(node);
    }

    if(_getColor(node->left) == RED &&
       _getColor(node->left->left) == RED){
      node = _rotateRight(node);
    }

    if(_getColor(node->left) == RED && _getColor(node->right) == RED){
      _flipColors(node);
    }

    node->size = 1 + _size(node->left) + _size(node->right);
    return node;
  }  

  Node<K, V>* _deleteMin(Node<K, V>* node){
    if(node == null) return null;
    
    if(node->left == null){
      delete node;
      node = null;
      return null;
    }

    if(_getColor(node->left) == BLACK &&
       _getColor(node->left->left) == BLACK){
      node = _moveRedLeft(node);
    }

    node->left = _deleteMin(node->left);
    return _balance(node);
  }

  Node<K, V>* _deleteMax(Node<K, V>* node){
    if(_getColor(node->left) == RED) node = _rotateRight(node);
                                                                   
    if(node->right == null){
      delete node;
      node = null;
      return null;
    }

    if(_getColor(node->right) == BLACK && _getColor(node->right->left) == BLACK){
      node = _moveRedRight(node);
    }

    node->right = _deleteMax(node->right);
    return _balance(node);
  }

  Node<K, V>* _erase(Node<K, V>* node, const K& key){
    if(key < node->key){
      if(node->left == null) return null;  // Not found.
      if(_getColor(node->left) == BLACK &&
         _getColor(node->left->left) == BLACK){
        node = _moveRedLeft(node);
      }
      node->left = _erase(node->left, key);
    }else{      
      if(_getColor(node->left) == RED){
        node = _rotateRight(node);

        if(key == node->key && node->right == null){
          delete node;
          node = null;
          return null;
        }
      }else{
        if(key == node->key && node->right == null){
          delete node;
          node = null;
          return null;
        }
        
        if(_getColor(node->right) == BLACK &&
           _getColor(node->right->left) == BLACK){
          node = _moveRedRight(node);
        }
      }      

      if(key == node->key){
        node->key = _min(node->right)->key;
        node->val = _min(node->right)->val;
        node->right = _deleteMin(node->right);
      }else{
        node->right = _erase(node->right, key);
      }
    }

    return _balance(node);
  }

  Node<K, V>* _min(Node<K, V>* node) {
    Node<K, V>* temp = node->left;
    while(temp != null){
      node = temp;
      temp = temp->left;
    }

    return node;
  }

  Node<K, V> const* _min(const Node<K, V>* node) const{
    return _min(node);
  }

  Node<K, V>* _max(Node<K, V>* node) {
    Node<K, V>* temp = node->right;
    while(temp != null){
      node = temp;
      temp = temp->right;
    }

    return node;
  }

  Node<K, V> const* _max(const Node<K, V>* node) const{
    return _max(node);
  }

  Node<K, V>* _get(Node<K, V>* node, const K& key) {
    if(node == null){
      return null;
    }
    if(key < node->key){
      return _get(node->left, key);
    }else if(key > node->key){
      return _get(node->right, key);
    }else{
      return node;
    }
  }
  
  Node<K, V> const* _get(Node<K, V> const* node, const K& key) const{
    if(node == null){
      return null;
    }
    if(key < node->key){
      return _get(node->left, key);
    }else if(key > node->key){
      return _get(node->right, key);
    }else{
      return node;
    }
  }

  Node<K, V>* _moveRedLeft(Node<K, V>* node){
    _flipColors(node);
    if(_getColor(node->right->left) == RED){
      node->right = _rotateRight(node->right);
      node = _rotateLeft(node);
      _flipColors(node);
    }

    return node;
  }

  /**
   * @param node to apply moveRight method. Assumes that node is red,
   *             node->right and node->right->left are black.
   * @return new root node of the subtree.
   */
  Node<K, V>* _moveRedRight(Node<K, V>* node){
    _flipColors(node);
    if(_getColor(node->left->left) == RED){
      node = _rotateRight(node);
      _flipColors(node);
    }
    return node;
  }

  Color _getColor(Node<K, V>* node){
    if(node == nullptr){
      return BLACK;
    }else{
      return node->color;
    }
  }

  Node<K, V>* _rotateLeft(Node<K, V>* node){
    Node<K, V>* tempNode = node->right;
    node->right = tempNode->left;
    tempNode->left = node;
    tempNode->color = node->color;
    node->color = RED;
    tempNode->size = node->size;
    node->size = 1 + _size(node->left) + _size(node->right);
    return tempNode;
  }

  Node<K, V>* _rotateRight(Node<K, V>* node){
    Node<K, V>* tempNode = node->left;
    node->left = tempNode->right;
    tempNode->right = node;
    tempNode->color = node->color;
    node->color = RED;
    tempNode->size = node->size;
    node->size = 1 + _size(node->left) + _size(node->right);
    return tempNode;
  }

  void _flipColors(Node<K, V>* node){
    node->color = !node->color;
    node->left->color = !node->left->color;
    node->right->color = !node->right->color;
  }

  Node<K, V>* _balance(Node<K, V>* node){
    // Traversing upwards.
    if(_getColor(node->left) == BLACK){
      // If node->left is black, and node->right is red, rotate left, undo rbt violation.
      if(_getColor(node->right) == RED){
        node = _rotateLeft(node);
      }
    }else{
      // If node->left and node->left->left are red rotate right. Undo rbt violation.
      if(_getColor(node->left->left) == RED){
        node = _rotateRight(node);
      }

      // If node->left and node->right are red, Flip colors (break 4-node).
      if(_getColor(node->right) == RED){
        _flipColors(node);
      }
    }

    node->size = 1 + _size(node->left) + _size(node->right);
    return node;
  }

  size_t _height(Node<K, V> const* node) const{
    if(node != null){
      if(node->color == BLACK)
        return 1 + _height(node->left);
      else
        return _height(node->left);
    }
    
    return 0;
  }

  size_t _height(Node<K, V> const* node, const K& key) const{    
    if(node != null){        
      if(key < node->key){
        if(node->color == BLACK)
          return 1 + _height(node->left, key);
        else
          return _height(node->left, key);
      }else if(key > node->key){
        if(node->color == BLACK)
          return 1 + _height(node->right, key);
        else
          return _height(node->right, key);
      }else{
        if(node->color == BLACK)
          return 1;
        return 0;
      }
    }

    return 0;
  }

  size_t _size(const Node<K, V>* node) const{
    if(node == null){
      return 0;
    }else{
      return node->size;
    }
  }
  
 protected:
  Node<K, V>* _root = null;
};

/*!@class RedBlackTree
 * @brief Red Black Tree implementation.
 */
template <typename K, typename V>
class RedBlackTree final : public RedBlackTreeAbstract<K, V>{
  // Hide some methods.
private:
  virtual size_t getHeight() const override{
    return RedBlackTreeAbstract<K, V>::getHeight();
  }

  virtual size_t getHeight(const K& key) const override{
    return RedBlackTreeAbstract<K, V>::getHeight(key);
  }

  virtual bool isLeaf(const K& key) const{
    return RedBlackTreeAbstract<K, V>::isLeaf(key);
  }
}; 

}  // Algorithm
