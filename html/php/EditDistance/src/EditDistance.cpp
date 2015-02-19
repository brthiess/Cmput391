/**
 * EditDistance.cpp
 */

#include <vector>
#include <algorithm>
#include <iostream>

#include "EditDistance.h"

using namespace std;

namespace Algorithm{

size_t editDistance(const string& str1, const string& str2){
  vector< vector<size_t> > dist(str1.size()+1, vector<size_t>(str2.size()+1, 0));

  for(size_t i = 1; i < str1.size(); i++){
    dist[i][0] = i;
  }

  for(size_t i = 1; i < str2.size(); i++){
    dist[0][i] = i;
  }

  for(size_t i = 1; i < str1.size()+1; i++){
    for(size_t j = 1; j < str2.size()+1; j++){
      dist[i][j] =
          std::min(dist[i-1][j]+1, std::min(dist[i][j-1]+1,
                                            dist[i-1][j-1] + (str1[i-1] != str2[j-1])));
    }
  }
  
  return dist[str1.size()][str2.size()];
}

}
