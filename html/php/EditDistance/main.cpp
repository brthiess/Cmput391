/**
 * main.cpp
 */

#include <cassert>
#include <iostream>

#include "EditDistance.h"

int main(int argc, char** args){
  assert(argc >= 3);

  cout << Algorithm::editDistance(args[1], args[2]) << endl;
  
  return 0;
}
